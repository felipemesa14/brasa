<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuVacacionRepository extends EntityRepository {        
    
    public function listaVacacionesDQL($strCodigoCentroCosto = "", $strIdentificacion = "", $boolEstadoPagado = "", $boolEstadoAutorizado = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT v, e FROM BrasaRecursoHumanoBundle:RhuVacacion v JOIN v.empleadoRel e WHERE v.codigoVacacionPk <> 0";
        
        if($strCodigoCentroCosto != "") {
            $dql .= " AND v.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }   
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion LIKE '%" . $strIdentificacion . "%'";
        }
        if($boolEstadoPagado == 1 ) {
            $dql .= " AND v.estadoPagoGenerado = 1";
        } 
        if($boolEstadoPagado == '0') {
            $dql .= " AND v.estadoPagoGenerado = 0";
        }
        if($boolEstadoAutorizado == 1 ) {
            $dql .= " AND v.estadoAutorizado = 1";
        } 
        if($boolEstadoAutorizado == '0') {
            $dql .= " AND v.estadoAutorizado = 0";
        }
        $dql .= " ORDER BY v.codigoVacacionPk DESC";
        return $dql;
    }

    public function liquidar($codigoVacacion) {        
        $em = $this->getEntityManager();
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1);
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();            
        $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoVacacion);                         
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $arVacacion->getContratoRel();
        $fechaDesdePeriodo = $arContrato->getFechaUltimoPagoVacaciones();                                
        $fechaHastaPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->diasPrestacionesHasta(360, $fechaDesdePeriodo);
        $intDias = ($arVacacion->getDiasDisfrutados() + $arVacacion->getDiasPagados()) * 24;
        $fechaDesdePeriodo = $arContrato->getFechaUltimoPagoVacaciones();
        $fechaHastaPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->diasPrestacionesHasta($intDias, $fechaDesdePeriodo);
        $arVacacion->setFechaDesdePeriodo($fechaDesdePeriodo);
        $arVacacion->setFechaHastaPeriodo($fechaHastaPeriodo);        
        $intDias = $arVacacion->getDiasVacaciones();
        $floSalario = $arVacacion->getEmpleadoRel()->getVrSalario();        
        //Analizar cambios de salario
        $fecha = $arVacacion->getFecha()->format('Y-m-d');
        $nuevafecha = strtotime ( '-90 day' , strtotime ( $fecha ) ) ;
        $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
        $fechaDesdeCambioSalario = date_create_from_format('Y-m-d H:i', $nuevafecha . " 00:00");        
        $floSalarioPromedio = 0;        
        /*$arCambiosSalario = new \Brasa\RecursoHumanoBundle\Entity\RhuCambioSalario();
        $arCambiosSalario = $em->getRepository('BrasaRecursoHumanoBundle:RhuCambioSalario')->cambiosSalario($arVacacion->getContratoRel()->getCodigoContratoPk(), $fechaDesdeCambioSalario->format('Y-m-d'), $arVacacion->getFecha()->format('Y-m-d'));                 
        if(count($arCambiosSalario) > 0) {
            $floPrimerSalario = $arCambiosSalario[0]->getVrSalarioAnterior();
            $intNumeroRegistros = count($arCambiosSalario) + 1;
            $floSumaSalarios = 0;
            foreach ($arCambiosSalario as $arCambioSalario) {
                $floSumaSalarios += $arCambioSalario->getVrSalarioNuevo();
            }
            $floSalarioPromedio = round((($floSumaSalarios + $floPrimerSalario) / $intNumeroRegistros));
            
        } else {
            $floSalarioPromedio = $floSalario;
        }         
         * 
         */
        $fechaDesdeRecargos = $arVacacion->getFecha()->format('Y-m-d');
        $nuevafecha = strtotime ( '-365 day' , strtotime ( $fechaDesdeRecargos ) ) ;
        $nuevafecha = date ( 'Y-m-d' , $nuevafecha );        
        $fechaDesde = date_create($nuevafecha);
        if($fechaDesde > $arContrato->getFechaDesde()) {            
            $fechaDesdeRecargos = $nuevafecha;
            $fechaHastaRecargos = $arVacacion->getFecha()->format('Y-m-d');
            $recargosNocturnos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->recargosNocturnos($fechaDesdeRecargos, $fechaHastaRecargos, $arContrato->getCodigoContratoPk());        
            $recargosNocturnos = $recargosNocturnos / 12;            
        } else {
            $fechaDesdeRecargos = $arContrato->getFechaDesde()->format('Y-m-d');
            $fechaHastaRecargos = $arVacacion->getFecha()->format('Y-m-d');
            $interval = date_diff($arContrato->getFechaDesde(), $arVacacion->getFecha()); 
            $meses = $interval->format('%m');
            if($meses <= 0) {
                $meses = 1;
            }
            $recargosNocturnos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->recargosNocturnos($fechaDesdeRecargos, $fechaHastaRecargos, $arContrato->getCodigoContratoPk());        
            $recargosNocturnos = $recargosNocturnos / $meses;                         
        }

        $recargosNocturnosInicial = $arContrato->getPromedioRecargoNocturnoInicial();
        $promedioRecargosNocturnos = $recargosNocturnosInicial + $recargosNocturnos;
        if ($promedioRecargosNocturnos == null){
            $promedioRecargosNocturnos = 0;
        }
        $arVacacion->setVrPromedioRecargoNocturno($promedioRecargosNocturnos);
        if($arContrato->getCodigoSalarioTipoFk() == 1) {
            $floSalarioPromedio = $arContrato->getVrSalario();
        } else {            
            $floSalarioPromedio = $arContrato->getVrSalario() + $promedioRecargosNocturnos;
        }       
        
        $floTotalVacacionBrutoDisfrute = $floSalarioPromedio / 30 * $arVacacion->getDiasDisfrutadosReales();
        if($arVacacion->getDiasDisfrutadosReales() > 1) {
            $floTotalVacacionBrutoPagados = $floSalarioPromedio / 30 * $arVacacion->getDiasPagados();            
        } else {            
            $floTotalVacacionBrutoPagados = $arContrato->getVrSalario() / 30 * $arVacacion->getDiasPagados();            
        }        
        $floTotalVacacionBruto = $floTotalVacacionBrutoDisfrute + $floTotalVacacionBrutoPagados;  
        
        $douSalud = ($floTotalVacacionBrutoDisfrute * 4) / 100;
        $arVacacion->setVrSalud($douSalud);
        if ($floTotalVacacionBruto >= ($arConfiguracion->getVrSalario() * 4)){
            $douPorcentaje = $arConfiguracion->getPorcentajePensionExtra();
            $douPension = ($floSalario * $douPorcentaje) /100;
        } else {
            $douPension = ($floTotalVacacionBrutoDisfrute * 4) / 100;
        }
        $arVacacion->setVrPension($douPension);                                   
        $floDeducciones = 0;
        $arVacacionDeducciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionCredito();
        $arVacacionDeducciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionCredito')->FindBy(array('codigoVacacionFk' => $codigoVacacion));        
        foreach ($arVacacionDeducciones as $arVacacionDeduccion) {
            $floDeducciones += $arVacacionDeduccion->getVrDeduccion();
        }
        $floBonificaciones = 0;
        $arVacacionBonificaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionBonificacion();
        $arVacacionBonificaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionBonificacion')->FindBy(array('codigoVacacionFk' => $codigoVacacion));        
        foreach ($arVacacionBonificaciones as $arVacacionBonificacion) {
            $floBonificaciones += $arVacacionBonificacion->getVrBonificacion();
        }        
        $arVacacion->setVrBonificacion($floBonificaciones);
        $arVacacion->setVrDeduccion($floDeducciones);
        $arVacacion->setVrVacacionBruto($floTotalVacacionBruto);
        $floTotalVacacion = ($floTotalVacacionBruto+$floBonificaciones) - $floDeducciones - $arVacacion->getVrPension() - $arVacacion->getVrSalud();        
        $arVacacion->setVrVacacion($floTotalVacacion);        
        $arVacacion->setVrSalarioActual($floSalario);
        $arVacacion->setVrSalarioPromedio($floSalarioPromedio);
        $em->persist($arVacacion);
        $em->flush();
        
        return true;
    } 
    
    public function pagar($codigoVacacion) {        
        $em = $this->getEntityManager();
        $validar = '';
        
        $arVacacionCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionCredito();
        $arVacacionCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionCredito')->findBy(array('codigoVacacionFk' => $codigoVacacion));                                 
        $deduccion = 0;
        if ($arVacacionCreditos != null){
            foreach ($arVacacionCreditos as $arVacacionCredito){
                if ($arVacacionCredito->getCodigoCreditoFk() != null){
                    $arCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($arVacacionCredito->getCodigoCreditoFk());
                    $deduccion = $arVacacionCredito->getVrDeduccion();
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
                        $arCreditoTipoPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuCreditoTipoPago')->find(3);
                        $arPagoCredito->setCreditoTipoPagoRel($arCreditoTipoPago);
                        $arPagoCredito->setVrCuota($deduccion);
                        $arPagoCredito->setSaldo($arCredito->getSaldo());
                        $arPagoCredito->setNumeroCuotaActual($arCredito->getNumeroCuotaActual());
                    }
                }    
            }
            if ($validar == '' && $deduccion != 0){
                if ($arCredito->getSaldo() <= 0){
                    $arCredito->setEstadoPagado(1);        
                    $em->persist($arCredito);
                }
                $em->persist($arCredito);
                $em->persist($arPagoCredito);
            }
        }    
        return $validar;
        
    }
    
    public function devuelveVacacionesFecha($codigoEmpleado, $fechaDesde, $fechaHasta) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(v.vrVacacion) as Vacaciones FROM BrasaRecursoHumanoBundle:RhuVacacion v "
                . "WHERE v.codigoEmpleadoFk = " . $codigoEmpleado 
                . "AND v.fechaDesdePeriodo >= '" . $fechaDesde . "' AND v.fechaHastaPeriodo <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        return $arrayResultado;
    }
    
    public function dias($codigoEmpleado, $codigoContrato, $fechaDesde, $fechaHasta) {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $arVacaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $dql = "SELECT v FROM BrasaRecursoHumanoBundle:RhuVacacion v "
                . "WHERE (((v.fechaDesdeDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (v.fechaHastaDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
                . "OR (v.fechaDesdeDisfrute >= '$strFechaDesde' AND v.fechaDesdeDisfrute <= '$strFechaHasta') "
                . "OR (v.fechaHastaDisfrute >= '$strFechaHasta' AND v.fechaDesdeDisfrute <= '$strFechaDesde')) "
                . "AND v.codigoEmpleadoFk = '" . $codigoEmpleado . "' AND v.codigoContratoFk = " . $codigoContrato . " AND v.diasDisfrutados > 0";
        
        $query = $em->createQuery($dql);
        $arVacaciones = $query->getResult();
        $intDiasDevolver = 0;
        foreach ($arVacaciones as $arVacacion) {
            $dateFechaDesde =  "";
            $dateFechaHasta =  "";                            
            if($arVacacion->getFechaDesdeDisfrute() <  $fechaDesde == true) {
                $dateFechaDesde = $fechaDesde;
            } else {
                $dateFechaDesde = $arVacacion->getFechaDesdeDisfrute();
            }

            if($arVacacion->getFechaHastaDisfrute() >  $fechaHasta == true) {
                $dateFechaHasta = $fechaHasta;
            } else {
                $dateFechaHasta = $arVacacion->getFechaHastaDisfrute();
            }
            if($dateFechaDesde != "" && $dateFechaHasta != "") {
                $intDias = $dateFechaDesde->diff($dateFechaHasta);
                $intDias = $intDias->format('%a');
                $intDiasDevolver += $intDias + 1;
            }                            
        }
        return $intDiasDevolver;
    }    
    
    //Seguridad social
    public function diasVacacionesDisfrute($fechaDesde, $fechaHasta, $codigoEmpleado, $codigoContrato) {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $dql = "SELECT v FROM BrasaRecursoHumanoBundle:RhuVacacion v "
                . "WHERE (((v.fechaDesdeDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (v.fechaHastaDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
                . "OR (v.fechaDesdeDisfrute >= '$strFechaDesde' AND v.fechaDesdeDisfrute <= '$strFechaHasta') "
                . "OR (v.fechaHastaDisfrute >= '$strFechaHasta' AND v.fechaDesdeDisfrute <= '$strFechaDesde')) "
                . "AND v.codigoEmpleadoFk = " . $codigoEmpleado . " AND v.codigoContratoFk = " . $codigoContrato;
        $objQuery = $em->createQuery($dql);  
        $arVacacionesDisfrute = $objQuery->getResult();        
        $intDiasVacacionesTotal = 0;
        $vrAporteParafiscales = 0;
        foreach ($arVacacionesDisfrute as $arVacacionDisfrute) {            
            $intDiasVacaciones = 0;
            $intDiaInicio = 1;            
            $intDiaFin = 30;
            if($arVacacionDisfrute->getFechaDesdeDisfrute() <  $fechaDesde) {
                $intDiaInicio = 1;                
            } else {
                $intDiaInicio = $arVacacionDisfrute->getFechaDesdeDisfrute()->format('j');
            }
            if($arVacacionDisfrute->getFechaHastaDisfrute() > $fechaHasta) {
                $intDiaFin = 30;                
            } else {
                $intDiaFin = $arVacacionDisfrute->getFechaHastaDisfrute()->format('j');
            }            
            $intDiasVacaciones = (($intDiaFin - $intDiaInicio)+1);
            if($intDiasVacaciones == 1) {
                $intDiasVacaciones = 0;
            }     
            $intDiasVacacionesTotal += $intDiasVacaciones;
            //$arVacacionDisfrute = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
            if($arVacacionDisfrute->getDiasDisfrutados() > 1) {
                $vrDiaDisfrute = ($arVacacionDisfrute->getVrVacacionBruto() / $arVacacionDisfrute->getDiasDisfrutados());    
                $vrAporteParafiscales += $intDiasVacaciones * $vrDiaDisfrute;
            } else {
                $vrAporteParafiscales += $arVacacionDisfrute->getVrVacacionBruto();
            }            
            
        }
        if($intDiasVacacionesTotal > 30) {
            $intDiasVacacionesTotal = 30;
        }
        $arrVacaciones = array('dias' => $intDiasVacacionesTotal, 'aporte' => $vrAporteParafiscales);
        return $arrVacaciones;                     
    }     
    
    
    public function periodo($fechaDesde, $fechaHasta, $codigoEmpleado = "", $codigoCentroCosto = "") {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $dql = "SELECT vacacion FROM BrasaRecursoHumanoBundle:RhuVacacion vacacion "
                . "WHERE (((vacacion.fechaDesdeDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (vacacion.fechaHastaDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
                . "OR (vacacion.fechaDesdeDisfrute >= '$strFechaDesde' AND vacacion.fechaDesdeDisfrute <= '$strFechaHasta') "
                . "OR (vacacion.fechaHastaDisfrute >= '$strFechaHasta' AND vacacion.fechaDesdeDisfrute <= '$strFechaDesde')) ";
        if($codigoEmpleado != "") {
            $dql = $dql . "AND vacacion.codigoEmpleadoFk = '" . $codigoEmpleado . "' ";
        }
        if($codigoCentroCosto != "") {
            $dql = $dql . "AND vacacion.codigoCentroCostoFk = " . $codigoCentroCosto . " ";
        }        

        $objQuery = $em->createQuery($dql);  
        $arVacaciones = $objQuery->getResult();         
        return $arVacaciones;
    }                        
}

