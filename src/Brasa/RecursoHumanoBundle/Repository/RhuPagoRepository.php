<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuPagoRepository extends EntityRepository {
    
    public function liquidar($codigoPago) {        
        $em = $this->getEntityManager();
        $arPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
        $arPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => $codigoPago)); 
        $douSalario = 0;
        $douAuxilioTransporte = 0;
        $douAdicionTiempo = 0;
        $douAdicionValor = 0;
        $douPension = 0;
        $douCaja = 0;
        $douCesantias = 0;
        $douVacaciones = 0;
        $douAdministracion = 0;
        $douDeducciones = 0;
        $douDevengado = 0;        
        $douNeto = 0;
        $douIngresoBaseCotizacion = 0;
        foreach ($arPagoDetalles as $arPagoDetalle) {
            if($arPagoDetalle->getOperacion() == 1) {
                $douDevengado = $douDevengado + $arPagoDetalle->getVrPago();
            }
            if($arPagoDetalle->getOperacion() == -1) {
                $douDeducciones = $douDeducciones + $arPagoDetalle->getVrPago();
            }
            if($arPagoDetalle->getPagoConceptoRel()->getComponeSalario() == 1) {
                $douSalario = $douSalario + $arPagoDetalle->getVrPago();
            }            
            if($arPagoDetalle->getPagoConceptoRel()->getConceptoAuxilioTransporte() == 1) {
                $douAuxilioTransporte = $douAuxilioTransporte + $arPagoDetalle->getVrPago();
            }            
            if($arPagoDetalle->getPagoConceptoRel()->getConceptoAdicion() == 1) {
                if($arPagoDetalle->getPagoConceptoRel()->getComponeValor() == 1) {
                    $douAdicionValor = $douAdicionValor + $arPagoDetalle->getVrPago();    
                } else {
                    $douAdicionTiempo = $douAdicionTiempo + $arPagoDetalle->getVrPago();    
                }                
            }
            $douIngresoBaseCotizacion = $douIngresoBaseCotizacion + $arPagoDetalle->getVrIngresoBaseCotizacion();
            
        }
        
        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($codigoPago);
        $douSalarioPeriodo = $arPago->getVrSalarioPeriodo();
        $douSalarioSeguridadSocial = $douSalarioPeriodo + $douAdicionTiempo + $douAdicionValor;
        $douDiaAuxilioTransporte = 74000 / 30;
        $douAuxilioTransporteCotizacion = $arPago->getDiasPeriodo() * $douDiaAuxilioTransporte;
        $douArp = ($douSalarioSeguridadSocial * $arPago->getEmpleadoRel()->getClasificacionRiesgoRel()->getPorcentaje())/100;        
        $douPension = ($douSalarioSeguridadSocial * $arPago->getEmpleadoRel()->getTipoPensionRel()->getPorcentajeCotizacion()) / 100; 
        $douCaja = ($douSalarioSeguridadSocial * 4) / 100; // este porcentaje debe parametrizarse en configuracion                
        $douCesantias = (($douSalarioSeguridadSocial + $douAuxilioTransporteCotizacion) * 17.66) / 100; // este porcentaje debe parametrizarse en configuracion                
        $douVacaciones = ($douSalarioPeriodo * 4.5) / 100; // este porcentaje debe parametrizarse en configuracion                        
        $douTotalEjercicio = $douSalario+$douAdicionTiempo+$douAdicionValor+$douAuxilioTransporte+$douArp+$douPension+$douCaja+$douCesantias+$douVacaciones;
        if($arPago->getCentroCostoRel()->getPorcentajeAdministracion() != 0 ) {
            $douAdministracion = ($douTotalEjercicio * $arPago->getCentroCostoRel()->getPorcentajeAdministracion()) / 100;            
        } else {
            $douAdministracion = $arPago->getCentroCostoRel()->getPorcentajeAdministracion();
        }
        
        $arPago->setVrDevengado($douDevengado);
        $arPago->setVrDeducciones($douDeducciones);
        $douNeto = $douDevengado - $douDeducciones;
        $arPago->setVrNeto($douNeto);
        $arPago->setVrSalario($douSalario);
        $arPago->setVrAuxilioTransporte($douAuxilioTransporte);
        $arPago->setVrAuxilioTransporteCotizacion($douAuxilioTransporteCotizacion);
        $arPago->setVrAdicionalTiempo($douAdicionTiempo);
        $arPago->setVrAdicionalValor($douAdicionValor);
        $arPago->setVrArp($douArp);
        $arPago->setVrPension($douPension);
        $arPago->setVrCaja($douCaja);
        $arPago->setVrCesantias($douCesantias);
        $arPago->setVrVacaciones($douVacaciones);
        $arPago->setVrAdministracion($douAdministracion);
        //Tambien llamado total ejercicio
        $arPago->setVrCosto($douTotalEjercicio);
        $arPago->setVrTotalCobrar($douTotalEjercicio + $douAdministracion);        
        $arPago->setVrIngresoBaseCotizacion($douIngresoBaseCotizacion);
        $em->persist($arPago);
        $em->flush();
        return $douNeto;
    }    
    
    public function listaDql($intNumero = 0, $strCodigoCentroCosto = "", $strIdentificacion = "", $intTipo = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p, e FROM BrasaRecursoHumanoBundle:RhuPago p JOIN p.empleadoRel e WHERE p.codigoPagoPk <> 0";
        if($intNumero != "" && $intNumero != 0) {
            $dql .= " AND p.numero = " . $intNumero;
        }
        if($strCodigoCentroCosto != "") {
            $dql .= " AND p.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }   
        if($intTipo != "" && $intTipo != 0) {
            $dql .= " AND p.codigoPagoTipoFk =" . $intTipo;
        }        
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }        
        $dql .= " ORDER BY p.codigoPagoPk DESC";
        return $dql;
    }                        

    public function listaDqlCostos($intNumero = 0, $strCodigoCentroCosto = "", $strIdentificacion = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p, e FROM BrasaRecursoHumanoBundle:RhuPago p JOIN p.empleadoRel e WHERE p.codigoPagoTipoFk = 1 AND p.estadoPagado = 1";
        if($intNumero != "" && $intNumero != 0) {
            $dql .= " AND p.numero = " . $intNumero;
        }
        if($strCodigoCentroCosto != "") {
            $dql .= " AND p.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }   
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }        
        //$dql .= " ORDER BY p.empleadoRel.nombreCorto";
        return $dql;
    }                            
    
    public function generarPagoDetalleSede ($codigoPago) {
        $em = $this->getEntityManager();
        $arPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
        $arPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => $codigoPago));
        foreach ($arPagoDetalles as $arPagoDetalle) {
            $arProgramacionPagoDetalleSedes = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalleSede();
            $arProgramacionPagoDetalleSedes = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalleSede')->findBy(array('codigoProgramacionPagoDetalleFk' => $arPagoDetalle->getCodigoProgramacionPagoDetalleFk()));
            foreach ($arProgramacionPagoDetalleSedes as $arProgramacionPagoDetalleSede) {                
                $arPagoDetalleSede = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalleSede();
                $arPagoDetalleSede->setPagoRel($arPagoDetalle->getPagoRel());
                $arPagoDetalleSede->setPagoConceptoRel($arPagoDetalle->getPagoConceptoRel());                                                        
                $arPagoDetalleSede->setSedeRel($arProgramacionPagoDetalleSede->getSedeRel());                                                        
                $arPagoDetalleSede->setVrPago(($arPagoDetalle->getVrPago() * $arProgramacionPagoDetalleSede->getPorcentajeParticipacion()) / 100);
                $arPagoDetalleSede->setOperacion($arPagoDetalle->getOperacion());
                $arPagoDetalleSede->setPorcentajeAplicado($arPagoDetalle->getPorcentajeAplicado());
                $arPagoDetalleSede->setNumeroHoras(($arPagoDetalle->getNumeroHoras() * $arProgramacionPagoDetalleSede->getPorcentajeParticipacion()) / 100);
                $arPagoDetalleSede->setVrPagoOperado(($arPagoDetalle->getVrPagoOperado() * $arProgramacionPagoDetalleSede->getPorcentajeParticipacion()) / 100);                
                $arPagoDetalleSede->setVrTotal(($arPagoDetalle->getVrTotal() * $arProgramacionPagoDetalleSede->getPorcentajeParticipacion()) / 100);                                
                $arPagoDetalleSede->setVrIngresoBaseCotizacion(($arPagoDetalle->getVrIngresoBaseCotizacion() * $arProgramacionPagoDetalleSede->getPorcentajeParticipacion()) / 100);                                
                $em->persist($arPagoDetalleSede);
            }
        }
        $em->flush();
    }
    
    public function pendienteCobrar($codigoCentroCosto) {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p FROM BrasaRecursoHumanoBundle:RhuPago p WHERE p.estadoCobrado = 0 "
                . " AND p.codigoCentroCostoFk = " . $codigoCentroCosto;
        return $dql;
    }                              
    
    public function devuelveCostosFecha($codigoEmpleado, $fechaDesde, $fechaHasta) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(p.vrIngresoBaseCotizacion) as IBC, SUM(p.vrPension) as Pension, SUM(p.vrEps) as Salud, SUM(p.vrAuxilioTransporte) as AuxilioTransporte, MIN(p.fechaDesde) as fechaInicio, MAX(p.fechaHasta) as fechaFin FROM BrasaRecursoHumanoBundle:RhuPago p "
                . "WHERE p.codigoEmpleadoFk = " . $codigoEmpleado . " AND p.estadoPagado = 1"
                . "AND p.fechaDesde >= '" . $fechaDesde . "' AND p.fechaDesde <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        return $arrayResultado;
    }     
    
}