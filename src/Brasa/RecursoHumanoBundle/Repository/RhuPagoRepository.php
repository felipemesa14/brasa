<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuPagoRepository extends EntityRepository {
    
    public function Liquidar($codigoPago) {        
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
        $douArp = ($douIngresoBaseCotizacion * $arPago->getEmpleadoRel()->getClasificacionRiesgoRel()->getPorcentaje())/100;        
        $douPension = ($douIngresoBaseCotizacion * 12) / 100; // este porcentaje debe parametrizarse en configuracion
        $douCaja = ($douSalario * 4) / 100; // este porcentaje debe parametrizarse en configuracion                
        $douCesantias = (($douSalario + $douAuxilioTransporte) * 17.66) / 100; // este porcentaje debe parametrizarse en configuracion                
        $douVacaciones = ($douSalario * 4.5) / 100; // este porcentaje debe parametrizarse en configuracion                        
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
        $arPago->setVrAdicionalTiempo($douAdicionTiempo);
        $arPago->setVrAdicionalValor($douAdicionValor);
        $arPago->setVrArp($douArp);
        $arPago->setVrPension($douPension);
        $arPago->setVrCaja($douCaja);
        $arPago->setVrCesantias($douCesantias);
        $arPago->setVrVacaciones($douVacaciones);
        $arPago->setVrAdministracion($douAdministracion);
        $arPago->setVrTotalEjercicio($douTotalEjercicio);
        $arPago->setVrIngresoBaseCotizacion($douIngresoBaseCotizacion);
        $em->persist($arPago);
        $em->flush();
        return $douNeto;
    }    
    
    public function ListaDQL($intNumero = 0, $strCodigoCentroCosto = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p FROM BrasaRecursoHumanoBundle:RhuPago p WHERE p.codigoPagoPk <> 0";
        if($intNumero != "" && $intNumero != 0) {
            $dql .= " AND p.numero = " . $intNumero;
        }
        if($strCodigoCentroCosto != "") {
            $dql .= " AND p.codigoCentroCostoFk = " . $strCodigoCentroCosto;
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
    
}