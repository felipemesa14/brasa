<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuSsoPeriodoEmpleadoRepository extends EntityRepository {
    
    public function listaDql($codigoPeriodoDetalle, $strCodigoCentroCosto ) {                    
            $dql   = "SELECT pe, e FROM BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado pe JOIN pe.empleadoRel e "
                    ."WHERE pe.codigoPeriodoDetalleFk = " . $codigoPeriodoDetalle . " ";
            if($strCodigoCentroCosto != "") {
                $dql .= " AND e.codigoCentroCostoFk = " . $strCodigoCentroCosto;
            }
            return $dql;
    }
    
    public function listaTrasladoDql($codigoPeriodoDetalle, $strCodigoCentroCosto, $strCodigoSucursal) {                    
            $dql   = "SELECT pe, e FROM BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado pe JOIN pe.empleadoRel e "
                    ."WHERE pe.codigoPeriodoDetalleFk <> " . $codigoPeriodoDetalle . " ";
            if($strCodigoCentroCosto != "") {
                $dql .= " AND e.codigoCentroCostoFk = " . $strCodigoCentroCosto;
            }
            if($strCodigoSucursal != "") {
                $dql .= " AND pe.codigoPeriodoDetalleFk = " . $strCodigoSucursal;
            }
            return $dql;
    }
    
    public function listaCopiarDql($codigoPeriodoDetalle, $strCodigoCentroCosto, $strCodigoSucursal ) {                    
            $dql   = "SELECT pe, e FROM BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado pe JOIN pe.empleadoRel e "
                    ."WHERE pe.codigoPeriodoDetalleFk <> " . $codigoPeriodoDetalle . " ";
            if($strCodigoCentroCosto != "") {
                $dql .= " AND e.codigoCentroCostoFk = " . $strCodigoCentroCosto;
            }
            if($strCodigoSucursal != "") {
                $dql .= " AND pe.codigoPeriodoDetalleFk = " . $strCodigoSucursal;
            }
            return $dql;
    }
        
    public function actualizar($codigoPeriodoDetalle) {
        $em = $this->getEntityManager();
        $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
        $arPeriodoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);             
        $arPeriodoEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();
        $arPeriodoEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->findBy(array('codigoPeriodoDetalleFk' => $codigoPeriodoDetalle));     
        foreach ($arPeriodoEmpleados as $arPeriodoEmpleado) {
            $arPeriodoEmpleadoActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();
            $arPeriodoEmpleadoActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->find($arPeriodoEmpleado->getCodigoPeriodoEmpleadoPk());                        
            $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
            $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arPeriodoEmpleado->getCodigoContratoFk());                            
            $dateFechaDesde =  "";
            $dateFechaHasta =  "";
            $strNovedadIngreso = " ";
            $strNovedadRetiro = " ";
            $intDiasCotizar = 0;
            $fechaTerminaCotrato = $arContrato->getFechaHasta()->format('Y-m-d');
            if($arContrato->getIndefinido() == 1) {
                $dateFechaHasta = $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta();
            } else {
                if($arContrato->getFechaHasta() > $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()) {
                    $dateFechaHasta = $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta();
                } else {
                    $dateFechaHasta = $arContrato->getFechaHasta();
                }
            }

            if($arContrato->getFechaDesde() <  $arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde() == true) {
                $dateFechaDesde = $arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde();
            } else {
                $dateFechaDesde = $arContrato->getFechaDesde();
            }

            if($dateFechaDesde != "" && $dateFechaHasta != "") {
                $intDias = $dateFechaDesde->diff($dateFechaHasta);
                $intDias = $intDias->format('%a');                        
                $intDiasCotizar = $intDias + 1;
                if($intDiasCotizar == 31) {
                    $intDiasCotizar = $intDiasCotizar - 1;
                } else {
                    if($arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()->format('d') == 28) {
                        if($arContrato->getFechaHasta() >= $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta() || $arContrato->getIndefinido() == 1) {
                            $intDiasCotizar = $intDiasCotizar + 2;
                        }
                    }
                    if($arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()->format('d') == 29) {
                        if($arContrato->getFechaHasta() >= $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta() || $arContrato->getIndefinido() == 1) {
                            $intDiasCotizar = $intDiasCotizar + 1;
                        }
                    }                    
                    if($arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()->format('d') == 31) {
                        if($arContrato->getFechaHasta() >= $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta() || $arContrato->getIndefinido() == 1) {
                            if($arContrato->getFechaDesde()->format('d') != 31) {
                                $intDiasCotizar = $intDiasCotizar - 1;
                            }                                    
                        }
                    }                            
                }
            }
            if($arContrato->getFechaDesde() >= $arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde()) {
                $strNovedadIngreso = "X";
            }
            if($arContrato->getIndefinido() == 0 && $fechaTerminaCotrato <= $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()) {                    
                $strNovedadRetiro = "X";                    
            }
            $floSalario = $arContrato->getVrSalario();
            if($arContrato->getSalarioIntegral() == 1) {
                $arPeriodoEmpleadoActualizar->setSalarioIntegral('X');
            }
            $arPeriodoEmpleadoActualizar->setVrSalario($floSalario);
            $arPeriodoEmpleadoActualizar->setDias($intDiasCotizar);
            $arPeriodoEmpleadoActualizar->setIngreso($strNovedadIngreso);
            $arPeriodoEmpleadoActualizar->setRetiro($strNovedadRetiro);
            $floSuplementario = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->tiempoSuplementario($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde()->format('Y-m-d'), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()->format('Y-m-d'), $arContrato->getCodigoContratoPk());            
            $arPeriodoEmpleadoActualizar->setVrSuplementario($floSuplementario);
            $intDiasLicencia = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->diasLicencia($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta(), $arPeriodoEmpleado->getCodigoEmpleadoFk(), 2);
            $arPeriodoEmpleadoActualizar->setDiasLicencia($intDiasLicencia);          
            $intDiasIncapacidadGeneral = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->diasIncapacidad($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta(), $arPeriodoEmpleado->getCodigoEmpleadoFk(), 1);
            $arPeriodoEmpleadoActualizar->setDiasIncapacidadGeneral($intDiasIncapacidadGeneral);
            $intDiasLicenciaMaternidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->diasLicencia($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta(), $arPeriodoEmpleado->getCodigoEmpleadoFk(), 1);
            $arPeriodoEmpleadoActualizar->setDiasLicenciaMaternidad($intDiasLicenciaMaternidad);
            $intDiasIncapacidadLaboral = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->diasIncapacidad($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta(), $arPeriodoEmpleado->getCodigoEmpleadoFk(), 2);
            $arPeriodoEmpleadoActualizar->setDiasIncapacidadLaboral($intDiasIncapacidadLaboral);                        
            $intDiasVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->diasVacacionesDisfrute($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta(), $arPeriodoEmpleado->getCodigoEmpleadoFk(), $arPeriodoEmpleado->getCodigoContratoFk());
            $arPeriodoEmpleadoActualizar->setDiasVacaciones($intDiasVacaciones);            
            $arPeriodoEmpleadoActualizar->setTarifaPension($arContrato->getTipoPensionRel()->getPorcentajeCotizacion());
            $arPeriodoEmpleadoActualizar->setTarifaRiesgos($arContrato->getClasificacionRiesgoRel()->getPorcentaje());
            $arPeriodoEmpleadoActualizar->setCodigoEntidadPensionPertenece($arContrato->getEntidadPensionRel()->getCodigoInterface());
            $arPeriodoEmpleadoActualizar->setCodigoEntidadSaludPertenece($arContrato->getEntidadSaludRel()->getCodigoInterface());
            $arPeriodoEmpleadoActualizar->setCodigoEntidadCajaPertenece($arContrato->getEntidadCajaRel()->getCodigoInterface());
            $em->persist($arPeriodoEmpleadoActualizar);
            $arPeriodoDetalle->setEstadoActualizado(1);
            $em->persist($arPeriodoDetalle);
        }
        $em->flush();            
        return true;
    }
}