<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuLiquidacionRepository extends EntityRepository {
    
    public function listaDql($strIdentificacion = "", $boolEstadoGenerado = "", $strCodigoCentroCosto = "",$boolEstadoPagado = "") {        
        $dql   = "SELECT l, e FROM BrasaRecursoHumanoBundle:RhuLiquidacion l JOIN l.empleadoRel e WHERE l.codigoLiquidacionPk <> 0";
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion LIKE '%" . $strIdentificacion . "%'";
        }
        if($boolEstadoGenerado == 1 ) {
            $dql .= " AND l.estadoGenerado = 1";
        } 
        if($boolEstadoGenerado == '0') {
            $dql .= " AND l.estadoGenerado = 0";
        }
        if($boolEstadoPagado == 1 ) {
            $dql .= " AND l.estadoPagoGenerado = 1";
        } 
        if($boolEstadoPagado == '0') {
            $dql .= " AND l.estadoPagoGenerado = 0";
        }
        if($strCodigoCentroCosto != "") {
            $dql .= " AND l.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }
        $dql .= " ORDER BY l.codigoLiquidacionPk DESC";
        return $dql;
    }  
    
    public function liquidar($codigoLiquidacion) {        
        $em = $this->getEntityManager();
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1);
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();                
        $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigoLiquidacion); 
        $douSalario = 0;
        $douCesantias = 0;
        $douInteresesCesantias = 0;
        $douPrima = 0;
        $douAdicionalesPrima = 0;
        $douVacaciones = 0;    
        $intDiasLaborados = 0;
        $ibpCesantias = 0;        
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();           
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arLiquidacion->getCodigoContratoFk());         
        $arLiquidacion->setFechaHasta($arContrato->getFechaHasta());
        if($arLiquidacion->getLiquidarManual() == 0) {            
            $douIBPAdicional = 0;
            $dateFechaUltimoPago = $arLiquidacion->getContratoRel()->getFechaUltimoPago();                        
            //Ibc de dias adicionales
            if($dateFechaUltimoPago != null) {
                $arLiquidacion->setFechaUltimoPago($dateFechaUltimoPago);
                if($arLiquidacion->getFechaUltimoPago() < $arLiquidacion->getFechaHasta()) {
                    $dateFechaUltimoPagoLiquidacion = $arLiquidacion->getFechaUltimoPago();
                    date_add($dateFechaUltimoPagoLiquidacion, date_interval_create_from_date_string('1 days'));                
                    $diasAdicionales = $this->diasPrestaciones($dateFechaUltimoPagoLiquidacion, $arLiquidacion->getFechaHasta());
                    $douIBPAdicional = ($arLiquidacion->getContratoRel()->getVrSalarioPago()/30) * $diasAdicionales;
                    $arLiquidacion->setVrIngresoBasePrestacionAdicional($douIBPAdicional);                
                    $arLiquidacion->setDiasAdicionalesIBP($diasAdicionales);
                } else {
                    $arLiquidacion->setVrIngresoBasePrestacionAdicional(0);                
                    $arLiquidacion->setDiasAdicionalesIBP(0);                    
                }                
            }                   
            
            $intDiasLaborados = $this->diasPrestaciones($arLiquidacion->getContratoRel()->getFechaDesde(), $arLiquidacion->getContratoRel()->getFechaHasta());
            $douSalario = $arLiquidacion->getContratoRel()->getVrSalarioPago();  

            //Liquidar cesantias
            if($arLiquidacion->getLiquidarCesantias() == 1) {                  
                $dateFechaDesde = $arLiquidacion->getContratoRel()->getFechaUltimoPagoCesantias();            
                $dateFechaHasta = $arLiquidacion->getContratoRel()->getFechaHasta();
                $ibpCesantiasInicial = $arContrato->getIbpCesantiasInicial();                            
                $ibpCesantias = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->ibp($dateFechaDesde->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'), $arLiquidacion->getCodigoContratoFk());                
                $ibpCesantias += $ibpCesantiasInicial+$douIBPAdicional;                  
                //$intDiasAusentismo = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->diasAusentismo($dateFechaDesde->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'), $arLiquidacion->getCodigoContratoFk());                            
                $intDiasCesantias = $this->diasPrestaciones($dateFechaDesde, $dateFechaHasta);  
                $salarioPromedioCesantias = ($ibpCesantias / $intDiasCesantias) * 30;                
                if($arLiquidacion->getPorcentajeIbp() > 0) {
                    $salarioPromedioCesantias = ($salarioPromedioCesantias * $arLiquidacion->getPorcentajeIbp())/100;
                }
                if($arLiquidacion->getLiquidarSalario() == true) {
                    $salarioPromedioCesantias = $douSalario;
                }
                $douCesantias = ($salarioPromedioCesantias * $intDiasCesantias) / 360;          
                $floPorcentajeIntereses = (($intDiasCesantias * 12) / 360)/100;
                $douInteresesCesantias = $douCesantias * $floPorcentajeIntereses;
                $arLiquidacion->setFechaUltimoPagoCesantias($dateFechaDesde);
                $arLiquidacion->setDiasCesantias($intDiasCesantias);                
                $arLiquidacion->setVrCesantias($douCesantias);
                $arLiquidacion->setVrInteresesCesantias($douInteresesCesantias);                 
                $arLiquidacion->setVrIngresoBasePrestacionCesantias($ibpCesantias);
                $arLiquidacion->setVrIngresoBasePrestacionCesantiasInicial($ibpCesantiasInicial); 
                $arLiquidacion->setVrSalarioPromedioCesantias($salarioPromedioCesantias);
            }
            //Liquidar primas
            if($arLiquidacion->getLiquidarPrima() == 1) {            
                $dateFechaDesde = $arLiquidacion->getContratoRel()->getFechaUltimoPagoPrimas();
                $dateFechaHasta = $arLiquidacion->getContratoRel()->getFechaHasta();
                $intDiasPrima = 0;
                //$intDiasAusentismo = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->diasAusentismo($dateFechaDesde->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'), $arLiquidacion->getCodigoContratoFk());                                        
                if($dateFechaDesde <= $dateFechaHasta) {
                    $intDiasPrima = $this->diasPrestaciones($dateFechaDesde, $dateFechaHasta);    
                    $intDiasPrimaLiquidar = $intDiasPrima;
                    if($dateFechaDesde->format('m-d') == '06-30' || $dateFechaDesde->format('m-d') == '12-30') {
                        $intDiasPrimaLiquidar = $intDiasPrimaLiquidar - 1;
                    }
                    $ibpPrimasInicial = $arContrato->getIbpPrimasInicial();                    
                    $ibpPrimas = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->ibp($dateFechaDesde->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'), $arLiquidacion->getCodigoContratoFk());                
                    $ibpPrimas += $ibpPrimasInicial + $douIBPAdicional;                        
                    $salarioPromedioPrimas = ($ibpPrimas / $intDiasPrimaLiquidar) * 30; 
                    if($arLiquidacion->getPorcentajeIbp() > 0) {
                        $salarioPromedioPrimas = ($salarioPromedioPrimas * $arLiquidacion->getPorcentajeIbp())/100;
                    }
                    if($arLiquidacion->getLiquidarSalario() == true) {
                        $salarioPromedioPrimas = $douSalario;
                    }                    
                    $douPrima = ($salarioPromedioPrimas * $intDiasPrimaLiquidar) / 360;                
                    $arLiquidacion->setDiasPrimas($intDiasPrimaLiquidar);                    
                    $arLiquidacion->setVrPrima($douPrima);    
                    $arLiquidacion->setVrIngresoBasePrestacionPrimas($ibpPrimas);
                    $arLiquidacion->setVrIngresoBasePrestacionPrimasInicial($ibpPrimasInicial);
                    $arLiquidacion->setVrSalarioPromedioPrimas($salarioPromedioPrimas);
                } else {                    
                        /*$douIbp = $em->getRepository('BrasaRecursoHumanoBundle:RhuIngresoBase')->devuelveIbpFecha($arLiquidacion->getCodigoEmpleadoFk(), $arLiquidacion->getContratoRel()->getFechaDesde()->format('Y-m-d'), $arLiquidacion->getContratoRel()->getFechaHasta()->format('Y-m-d'), $arLiquidacion->getCodigoContratoFk());                
                        $douIbp = $douIbp + $floIBPAdicionalContrato;
                        $douIBPTotalPrimas = $douIbp;
                        $intDiasPrima = $this->diasPrestaciones($dateFechaHasta, $dateFechaDesde) - 2;    
                        $douBasePrestacionesPrimas = ($douIBPTotalPrimas / $intDiasPrima) * 30;
                        $douBasePrestacionesTotalPrimas = ($douIBPTotalPrimas + $douAuxilioTransporte) * $intDiasPrima;

                        $douAdicionalesPrima = $douBasePrestacionesTotalPrimas / 360;                     
                        $arLiquidacion->setDiasPrimas($intDiasPrima * -1);
                        $arLiquidacion->setVrPrima(0); 
                        $arLiquidacion->setVrDeduccionPrima($douAdicionalesPrima);  
                         * 
                         */                                     
                }                                                                                
                $arLiquidacion->setFechaUltimoPagoPrimas($arLiquidacion->getContratoRel()->getFechaUltimoPagoPrimas());
            }
            //Liquidar vacaciones
            if($arLiquidacion->getContratoRel()->getFechaUltimoPagoVacaciones() < $arLiquidacion->getFechaHasta()) {
                if($arLiquidacion->getLiquidarVacaciones() == 1) { 
                    $dateFechaDesde = $arLiquidacion->getContratoRel()->getFechaUltimoPagoVacaciones();
                    $dateFechaHasta = $arLiquidacion->getContratoRel()->getFechaHasta();
                    $recargosNocturnos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->recargosNocturnosFecha($dateFechaDesde, $dateFechaHasta, $arLiquidacion->getContratoRel()->getCodigoContratoPk());
                    if($arLiquidacion->getCodigoMotivoTerminacionContratoFk() == 5) {
                         $salarioVacaciones = $douSalario + $recargosNocturnos;
                    } else {
                        $salarioVacaciones = $douSalario;
                    }                                        
                    $intDiasVacaciones = $this->diasPrestaciones($dateFechaDesde, $dateFechaHasta);                                
                    $intDiasVacacionesLiquidar = $intDiasVacaciones;
                    $douVacaciones = ($salarioVacaciones * $intDiasVacacionesLiquidar) / 720;                                
                    $arLiquidacion->setVrSalarioVacaciones($salarioVacaciones);
                    $arLiquidacion->setDiasVacaciones($intDiasVacaciones);
                    $arLiquidacion->setVrVacaciones($douVacaciones);
                    $arLiquidacion->setFechaUltimoPagoVacaciones($arLiquidacion->getContratoRel()->getFechaUltimoPagoVacaciones());
                }
            }                   
            
        } else {
            //$arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion(); 
            $douCesantias = $arLiquidacion->getVrCesantias();
            $douInteresesCesantias = $arLiquidacion->getVrInteresesCesantias();   
            $douPrima = $arLiquidacion->getVrPrima();
            $douVacaciones = $arLiquidacion->getVrVacaciones();
            
            $arLiquidacion->setFechaUltimoPago($arContrato->getFechaUltimoPago());
            $arLiquidacion->setFechaUltimoPagoCesantias($arContrato->getFechaUltimoPagoCesantias());
            $arLiquidacion->setFechaUltimoPagoPrimas($arContrato->getFechaUltimoPagoPrimas());
            $arLiquidacion->setFechaUltimoPagoVacaciones($arContrato->getFechaUltimoPagoVacaciones());
        }
        
        $floAdicionales = 0;
        $floDeducciones = 0;
        $arLiquidacionAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales();
        $arLiquidacionAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacionAdicionales')->FindBy(array('codigoLiquidacionFk' => $codigoLiquidacion));        
        foreach ($arLiquidacionAdicionales as $arLiquidacionAdicional) {
            $floDeducciones += $arLiquidacionAdicional->getVrDeduccion();
            $floAdicionales += $arLiquidacionAdicional->getVrBonificacion();
        }
        $douTotal = $douCesantias + $douInteresesCesantias + $douPrima + $douVacaciones;
        $douTotal = $douTotal + $floAdicionales - $douAdicionalesPrima - $floDeducciones;
        $arLiquidacion->setVrTotal($douTotal);
        $arLiquidacion->setVrSalario($douSalario);
        $arLiquidacion->setVrDeducciones($floDeducciones);
        $arLiquidacion->setVrBonificaciones($floAdicionales);
        $arLiquidacion->setNumeroDias($intDiasLaborados);
        $arLiquidacion->setEstadoGenerado(1);
        $arLiquidacion->setFechaInicioContrato($arLiquidacion->getContratoRel()->getFechaDesde());
        $em->flush();
        return true;
    }  
    
    public function pagar($codigoLiquidacion) {        
        $em = $this->getEntityManager();
        $validar = '';
        
        $arLiquidacionCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales();
        $arLiquidacionCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacionAdicionales')->findBy(array('codigoLiquidacionFk' => $codigoLiquidacion));                                 
        $deduccion = 0;
        if ($arLiquidacionCreditos != null){
            foreach ($arLiquidacionCreditos as $arLiquidacionCredito){
                if ($arLiquidacionCredito->getCodigoCreditoFk() != null){
                    $arCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($arLiquidacionCredito->getCodigoCreditoFk());
                    $deduccion = $arLiquidacionCredito->getVrDeduccion();
                    $saldo = $arCredito->getSaldo();
                    if ($saldo < $deduccion ){
                        $validar = 1;
                    } else {
                        $arCredito->setSaldo($saldo - $deduccion);
                        $arCredito->setSaldoTotal($arCredito->getSaldoTotal() - $deduccion );
                        $arCredito->setNumeroCuotaActual($arCredito->getNumeroCuotaActual() + 1);
                        $arPagoCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCreditoPago();
                        $arPagoCredito->setCreditoRel($arCredito);                        
                        $arPagoCredito->setfechaPago(new \ DateTime("now"));
                        $arCreditoTipoPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuCreditoTipoPago')->find(4);
                        $arPagoCredito->setCreditoTipoPagoRel($arCreditoTipoPago);
                        $arPagoCredito->setVrCuota($deduccion);
                        $arPagoCredito->setSaldo($arCredito->getSaldo());
                        $arPagoCredito->setNumeroCuotaActual($arCredito->getNumeroCuotaActual());
                    }
                }    
            }
            if ($validar == '' && $deduccion != 0){
                $em->persist($arCredito);
                if ($arCredito->getSaldo() <= 0){
                    $arCredito->setEstadoPagado(1);        
                    $em->persist($arCredito);
                }        
                $em->flush($arCredito);
                $em->persist($arPagoCredito);
            }
        }    
        return $validar;
        
    }
    
    public function liquidarAdicionales($codigoLiquidacion) {        
        $em = $this->getEntityManager();        
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigoLiquidacion); 
        $floAdicionales = 0;
        $floDeducciones = 0;
        $arLiquidacionAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales();
        $arLiquidacionAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacionAdicionales')->FindBy(array('codigoLiquidacionFk' => $codigoLiquidacion));        
        foreach ($arLiquidacionAdicionales as $arLiquidacionAdicional) {
            $floDeducciones += $arLiquidacionAdicional->getVrDeduccion();
            $floAdicionales += $arLiquidacionAdicional->getVrBonificacion();
        }
        $douTotal = $arLiquidacion->getVrCesantias() + $arLiquidacion->getVrInteresesCesantias() + $arLiquidacion->getVrPrima() + $arLiquidacion->getVrVacaciones();
        $douTotal = $douTotal - $arLiquidacion->getVrDeduccionPrima() - $floDeducciones + $floAdicionales;
        $arLiquidacion->setVrTotal($douTotal);                        
        $arLiquidacion->setVrDeducciones($floDeducciones);
        $arLiquidacion->setVrBonificaciones($floAdicionales);
        $em->persist($arLiquidacion);
        $em->flush();
        return true;
    }        
    
    public function devuelvePrestacionesSocialesFecha($codigoEmpleado, $fechaDesde, $fechaHasta) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(l.VrCesantias) as CesantiaseIntereses, SUM(l.VrInteresesCesantias) as InteresesCesantias,SUM(l.VrPrima) as Prima, SUM(l.VrVacaciones) as Vacaciones FROM BrasaRecursoHumanoBundle:RhuLiquidacion l "
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
    
    //prestaciones liquidadas Dane
    public function devuelveCostosPrestacionesDane($fechaDesde, $fechaHasta, $fechaProceso) {
        $em = $this->getEntityManager();
        $dql   = "SELECT l, c FROM BrasaRecursoHumanoBundle:RhuLiquidacion l JOIN l.contratoRel c WHERE l.codigoLiquidacionPk <> 0"
                . "AND l.fechaDesde >= '" . $fechaDesde . "' AND l.fechaDesde <= '" . $fechaHasta . "'";
                if ($fechaProceso != ""){
                    $dql .= " AND l.fechaDesde LIKE '%".$fechaProceso. "%' AND l.fechaHasta LIKE '%".$fechaProceso. "%'";
                }
                
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        return $arrayResultado;
    }
}