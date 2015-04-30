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
            
        }
        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($codigoPago);
        $douArp = ($douSalario * $arPago->getEmpleadoRel()->getClasificacionRiesgoRel()->getPorcentaje())/100;        
        $douPension = ($douSalario * 12) / 100; // este porcentaje debe parametrizarse en configuracion
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
        $em->persist($arPago);
        $em->flush();
        return $douNeto;
    }             
}