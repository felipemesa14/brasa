<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuLiquidacionRepository extends EntityRepository {
    
    public function listaDql($strIdentificacion = "", $boolEstadoGenerado = "") {        
        $dql   = "SELECT l, e FROM BrasaRecursoHumanoBundle:RhuLiquidacion l JOIN l.empleadoRel e WHERE l.codigoLiquidacionPk <> 0";
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion LIKE '%" . $strIdentificacion . "%'";
        }
        if($boolEstadoGenerado == 1 ) {
            $dql .= " AND l.estadoGenerado = 1";
        } elseif($boolEstadoGenerado == 0) {
            $dql .= " AND l.estadoGenerado = 0";
        }
        $dql .= " ORDER BY l.codigoLiquidacionPk";
        return $dql;
    }  
    
    public function liquidar($codigoLiquidacion) {        
        $em = $this->getEntityManager();
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1);
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigoLiquidacion); 
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        if ($arLiquidacion->getFecha()->format('Y') > $arConfiguracion->getAnioActual()){
            $objMensaje->Mensaje("error", "No se puede liquidar por que no se ha cerrado el año ".$arConfiguracion->getAnioActual()."", $this);
        } else {
            $boolPeriodoContinuo = $arLiquidacion->getContratoRel()->getCentroCostoRel()->getPeriodoPagoRel()->getContinuo();
            $douIBCAdicional = 0;
            $dateFechaUltimoPago = $arLiquidacion->getContratoRel()->getFechaUltimoPago();                        
            if($dateFechaUltimoPago != null) {
                $arLiquidacion->setFechaUltimoPago($dateFechaUltimoPago);
                if($arLiquidacion->getFechaUltimoPago() < $arLiquidacion->getFechaHasta()) {
                    $dateFechaUltimoPagoLiquidacion = $arLiquidacion->getFechaUltimoPago();
                    date_add($dateFechaUltimoPagoLiquidacion, date_interval_create_from_date_string('1 days'));                
                    $diasAdicionales = $this->diasPrestaciones($dateFechaUltimoPagoLiquidacion, $arLiquidacion->getFechaHasta());
                    $douIBCAdicional = ($arLiquidacion->getContratoRel()->getVrSalarioPago()/30) * $diasAdicionales;
                    $arLiquidacion->setVrIngresoBaseCotizacionAdicional($douIBCAdicional);                
                    $arLiquidacion->setDiasAdicionalesIBC($diasAdicionales);
                }
            }
            if($arLiquidacion->getContratoRel()->getFechaUltimoPagoCesantias() > $arLiquidacion->getContratoRel()->getFechaDesde()) {
                $fechaDesdePrestacional = $arLiquidacion->getContratoRel()->getFechaUltimoPagoCesantias();            
            } else {
                $fechaDesdePrestacional = $arLiquidacion->getContratoRel()->getFechaDesde();
            }

            $arrayCostos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->devuelveCostosFecha($arLiquidacion->getCodigoEmpleadoFk(), $fechaDesdePrestacional->format('Y-m-d'), $arLiquidacion->getContratoRel()->getFechaHasta()->format('Y-m-d'), $arLiquidacion->getCodigoContratoFk());        
            $douIBC = (float)$arrayCostos[0]['IBC']; 
            $douIBCTotal = $douIBC + $douIBCAdicional;
            $intDiasLaborados = $this->diasPrestaciones($fechaDesdePrestacional, $arLiquidacion->getContratoRel()->getFechaHasta());
            $douSalario = $arLiquidacion->getContratoRel()->getVrSalarioPago();
            $douBasePrestacionesTotal = 0;        
            $douCesantias = 0;
            $douInteresesCesantias = 0;
            $douPrima = 0;
            $douDeduccionPrima = 0;
            $douVacaciones = 0;
            $douAuxilioTransporte = 0;
            if($intDiasLaborados > 0) {
                if($boolPeriodoContinuo == 1) {
                    $intDiasContinuos = $this->diasContinuos($arLiquidacion->getContratoRel()->getFechaDesde(), $arLiquidacion->getContratoRel()->getFechaHasta());
                    $douBasePrestaciones = ($douIBCTotal / $intDiasContinuos) * 30;
                } else {
                    $douBasePrestaciones = ($douIBCTotal / $intDiasLaborados) * 30;
                }

                if($douSalario <= $arConfiguracion->getVrSalario() * 2) {
                    $douAuxilioTransporte = $arConfiguracion->getVrAuxilioTransporte();
                }

                $douBasePrestacionesTotal = $douBasePrestaciones + $douAuxilioTransporte;
                $arLiquidacion->setVrBasePrestaciones($douBasePrestaciones);
                $arLiquidacion->setVrAuxilioTransporte($douAuxilioTransporte);
                $arLiquidacion->setVrBasePrestacionesTotal($douBasePrestacionesTotal);            
            }  

            if($arLiquidacion->getLiquidarCesantias() == 1) {            
                $dateFechaDesde = $arLiquidacion->getContratoRel()->getFechaUltimoPagoCesantias();            
                $dateFechaHasta = $arLiquidacion->getContratoRel()->getFechaHasta();
                $intDiasCesantias = $this->diasPrestaciones($dateFechaDesde, $dateFechaHasta);                            
                $douCesantias = ($douBasePrestacionesTotal * $intDiasCesantias) / 360;          
                $floPorcentajeIntereses = (($intDiasCesantias * 12) / 360)/100;
                $douInteresesCesantias = $douCesantias * $floPorcentajeIntereses;
                $arLiquidacion->setFechaUltimoPagoCesantias($arLiquidacion->getContratoRel()->getFechaUltimoPagoCesantias());
                $arLiquidacion->setDiasCesantias($intDiasCesantias);
                $arLiquidacion->setVrCesantias($douCesantias);
                $arLiquidacion->setVrInteresesCesantias($douInteresesCesantias);                        
            }
            if($arLiquidacion->getLiquidarPrima() == 1) {            
                $dateFechaDesde = $arLiquidacion->getContratoRel()->getFechaUltimoPagoPrimas();
                $dateFechaHasta = $arLiquidacion->getContratoRel()->getFechaHasta();
                $intDiasPrima = 0;
                if($dateFechaDesde <= $dateFechaHasta) {
                    $intDiasPrima = $this->diasPrestaciones($dateFechaDesde, $dateFechaHasta);    
                    if($dateFechaDesde->format('m-d') == '06-30' || $dateFechaDesde->format('m-d') == '12-30') {
                        $intDiasPrima = $intDiasPrima - 1;
                    }

                    $douPrima = ($douBasePrestacionesTotal * $intDiasPrima) / 360;                
                    $arLiquidacion->setDiasPrimas($intDiasPrima);
                    $arLiquidacion->setVrPrima($douPrima);            
                } else {
                    $intDiasPrima = $this->diasPrestaciones($dateFechaHasta, $dateFechaDesde) - 2;    
                    $douDeduccionPrima = ($douBasePrestacionesTotal * $intDiasPrima) / 360;                
                    $arLiquidacion->setDiasPrimas($intDiasPrima * -1);
                    $arLiquidacion->setVrPrima(0); 
                    $arLiquidacion->setVrDeduccionPrima($douDeduccionPrima);                   
                }                                                                                
                $arLiquidacion->setFechaUltimoPagoPrimas($arLiquidacion->getContratoRel()->getFechaUltimoPagoPrimas());
            }

            if($arLiquidacion->getContratoRel()->getFechaUltimoPagoVacaciones() < $arLiquidacion->getFechaHasta()) {
                if($arLiquidacion->getLiquidarVacaciones() == 1) {
                    $floSalarioPromedio = 0;    
                    $fecha = $arLiquidacion->getFechaHasta()->format('Y-m-d');
                    $nuevafecha = strtotime ( '-90 day' , strtotime ( $fecha ) ) ;
                    $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
                    $fechaDesdeCambioSalario = date_create_from_format('Y-m-d H:i', $nuevafecha . " 00:00"); 

                    $arCambiosSalario = $em->getRepository('BrasaRecursoHumanoBundle:RhuCambioSalario')->cambiosSalario($arLiquidacion->getContratoRel()->getCodigoContratoPk(), $fechaDesdeCambioSalario->format('Y-m-d'), $arLiquidacion->getFechaHasta()->format('Y-m-d'));                 
                    if(count($arCambiosSalario) > 0) {
                        $floPrimerSalario = $arCambiosSalario[0]->getVrSalarioAnterior();
                        $intNumeroRegistros = count($arCambiosSalario) + 1;
                        $floSumaSalarios = 0;
                        foreach ($arCambiosSalario as $arCambioSalario) {
                            $floSumaSalarios += $arCambioSalario->getVrSalarioNuevo();
                        }
                        $floSalarioPromedio = round((($floSumaSalarios + $floPrimerSalario) / $intNumeroRegistros));

                    } else {
                        $floSalarioPromedio = $douSalario;
                    }                
                    $intDiasVacaciones = $this->diasPrestaciones($arLiquidacion->getContratoRel()->getFechaUltimoPagoVacaciones(), $arLiquidacion->getContratoRel()->getFechaHasta());                                
                    $douVacaciones = ($floSalarioPromedio * $intDiasVacaciones) / 720;
                    $arLiquidacion->setVrSalarioVacaciones($floSalarioPromedio);
                    $arLiquidacion->setDiasVacaciones($intDiasVacaciones);
                    $arLiquidacion->setVrVacaciones($douVacaciones);
                    $arLiquidacion->setFechaUltimoPagoVacaciones($arLiquidacion->getContratoRel()->getFechaUltimoPagoVacaciones());
                }
            }       
            $floDeducciones = 0;
            $arLiquidacionDeducciones = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionDeduccion();
            $arLiquidacionDeducciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacionDeduccion')->FindBy(array('codigoLiquidacionFk' => $codigoLiquidacion));        
            foreach ($arLiquidacionDeducciones as $arLiquidacionDeduccion) {
                $floDeducciones += $arLiquidacionDeduccion->getVrDeduccion();
            }
            $douTotal = $douCesantias + $douInteresesCesantias + $douPrima + $douVacaciones;
            $douTotal = $douTotal - $floDeducciones - $douDeduccionPrima;
            $arLiquidacion->setVrTotal($douTotal);
            $arLiquidacion->setVrSalario($douSalario);
            $arLiquidacion->setVrIngresoBaseCotizacion($douIBC);
            $arLiquidacion->setVrIngresoBaseCotizacionTotal($douIBCTotal); 
            $arLiquidacion->setVrDeducciones($floDeducciones);        
            $intDiasTotal = $arLiquidacion->getContratoRel()->getFechaDesde()->diff($arLiquidacion->getContratoRel()->getFechaHasta());
            $intDiasTotal = $intDiasTotal->format('%a');
            $arLiquidacion->setNumeroDias($intDiasLaborados);
            $arLiquidacion->setEstadoGenerado(1);
            $arLiquidacion->setFechaInicioContrato($arLiquidacion->getContratoRel()->getFechaDesde());
            $em->flush();
            return true;
        }
    }    
    
    public function liquidarDeducciones($codigoLiquidacion) {        
        $em = $this->getEntityManager();        
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigoLiquidacion); 
        $floDeducciones = 0;
        $arLiquidacionDeducciones = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionDeduccion();
        $arLiquidacionDeducciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacionDeduccion')->FindBy(array('codigoLiquidacionFk' => $codigoLiquidacion));        
        foreach ($arLiquidacionDeducciones as $arLiquidacionDeduccion) {
            $floDeducciones += $arLiquidacionDeduccion->getVrDeduccion();
        }
        $douTotal = $arLiquidacion->getVrCesantias() + $arLiquidacion->getVrInteresesCesantias() + $arLiquidacion->getVrPrima() + $arLiquidacion->getVrVacaciones();
        $douTotal = $douTotal - ($floDeducciones + $arLiquidacion->getVrDeduccionPrima());
        $arLiquidacion->setVrTotal($douTotal);                        
        $arLiquidacion->setVrDeducciones($floDeducciones);
        $em->persist($arLiquidacion);
        $em->flush();
        return true;
    }        
    
    public function devuelveCesantiasFecha($codigoEmpleado, $fechaDesde, $fechaHasta) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(l.VrCesantias) as Cesantias FROM BrasaRecursoHumanoBundle:RhuLiquidacion l "
                . "WHERE l.codigoEmpleadoFk = " . $codigoEmpleado 
                . "AND l.fechaDesde >= '" . $fechaDesde . "' AND l.fechaDesde <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        return $arrayResultado;
    } 
    
    public function diasPrestaciones($dateFechaDesde, $dateFechaHasta) {
        $intDias = 0;
        $intAnioInicio = $dateFechaDesde->format('Y');
        $intAnioFin = $dateFechaHasta->format('Y');
        $intAnios = 0;
        $intMeses = 0;
        if($dateFechaHasta >= $dateFechaDesde) {
            if($dateFechaHasta->format('d') == '31' && ($dateFechaHasta == $dateFechaDesde)){
                $intDias = 0;
            } else { 
                if($intAnioInicio != $intAnioFin) {
                    $intDiferenciaAnio = $intAnioFin - $intAnioInicio;            
                    if(($intDiferenciaAnio) > 1) {
                        $intAnios = $intDiferenciaAnio - 1;
                        $intAnios = $intAnios * 12 * 30;                        
                    }

                    $dateFechaTemporal = date_create_from_format('Y-m-d H:i', $intAnioInicio . '-12-30' . "00:00");
                    if($dateFechaDesde->format('n') != $dateFechaTemporal->format('n')) {                        
                        $intMeses = $dateFechaTemporal->format('n') - $dateFechaDesde->format('n') - 1;
                        $intDiasInicio = $this->diasPrestacionesMes($dateFechaDesde->format('j'), 1);
                        $intDiasFinal = $this->diasPrestacionesMes($dateFechaTemporal->format('j'), 0);
                        $intDias = $intDiasInicio + $intDiasFinal + ($intMeses * 30);
                    } else {
                        if($dateFechaTemporal->format('j') == $dateFechaDesde->format('j')) {
                            $intDias = 0;
                        } else {
                            $intDias = 1 + ($dateFechaTemporal->format('j') - $dateFechaDesde->format('j'));                               
                        }                
                    }

                    $dateFechaTemporal = date_create_from_format('Y-m-d H:i', $intAnioFin . '-01-01' . "00:00");
                    if($dateFechaTemporal->format('n') != $dateFechaHasta->format('n')) {                        
                        $intMeses = $dateFechaHasta->format('n') - $dateFechaTemporal->format('n') - 1;
                        $intDiasInicio = $this->diasPrestacionesMes($dateFechaTemporal->format('j'), 1);
                        $intDiasFinal = $this->diasPrestacionesMes($dateFechaHasta->format('j'), 0);
                        $intDias += $intDiasInicio + $intDiasFinal + ($intMeses * 30);
                    } else {
                        $intDias += 1 + ($dateFechaHasta->format('j') - $dateFechaTemporal->format('j'));                               
                    }
                    $intDias += $intAnios;
                } else {                                           
                    if($dateFechaDesde->format('n') != $dateFechaHasta->format('n')) {                        
                        $intMeses = $dateFechaHasta->format('n') - $dateFechaDesde->format('n') - 1;
                        $intDiasInicio = $this->diasPrestacionesMes($dateFechaDesde->format('j'), 1);
                        $intDiasFinal = $this->diasPrestacionesMes($dateFechaHasta->format('j'), 0);
                        $intDias = $intDiasInicio + $intDiasFinal + ($intMeses * 30);
                    } else {
                        if($dateFechaHasta->format('j') == 31) {
                            $intDias = ($dateFechaHasta->format('j') - $dateFechaDesde->format('j'));                                                                               
                        } else {
                            $intDias = 1 + ($dateFechaHasta->format('j') - $dateFechaDesde->format('j'));                                                                               
                        }
                        
                    }                        
                } 
            }
        } else {
            $intDias = 0;
        }
        return $intDias;
    }
    
    public function diasPrestacionesMes($intDia, $intDesde) {
        $intDiasDevolver = 0;
        if($intDesde == 1) {
            $intDiasDevolver = 31 - $intDia;
        } else {
            $intDiasDevolver = $intDia;
            if($intDia == 31) {
                $intDiasDevolver =  30;
            }
        }          
        return $intDiasDevolver;
    } 
    
    public function diasPrestacionesHasta($intDias, $dateFechaDesde) {
        $strFechaHasta = "";
        $intAnio = $dateFechaDesde->format('Y');
        $intMes = $dateFechaDesde->format('n');
        $intDia = $dateFechaDesde->format('j');
        $intDiasAcumulados = 1;
        $i = $intDia;
        while($intDiasAcumulados <= $intDias) {            
            //echo $intDiasAcumulados . "(" . $i . ")" . "(" . $intMes . ")" . "(" . $intAnio . ")" . "<br />";
            $fechaHastaPeriodo = $intAnio . "-" . $intMes . "-" . $i;
            if($i == 30) {
                $i = 1;                
                if($intMes == 12) {
                    $intMes = 1;
                    $intAnio++;
                } else {
                    $intMes++;                    
                }                    
            } else {
                $i++;                
            }            
            $intDiasAcumulados++;
        }
        
        $fechaHastaPeriodo = date_create_from_format('Y-n-j H:i', $fechaHastaPeriodo . " 00:00");                                                                                
        return $fechaHastaPeriodo;
    }
    
    public function diasContinuos($dateFechaDesde, $dateFechaHasta) {
        $intDias = 0;
        $intDias = $dateFechaDesde->diff($dateFechaHasta);
        $intDias = $intDias->format('%a');
        $intDias += 1;
        return $intDias;
    }
}