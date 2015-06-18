<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuLiquidacionRepository extends EntityRepository {
    
    public function listaDQL($strIdentificacion = "") {        
        $dql   = "SELECT l, e FROM BrasaRecursoHumanoBundle:RhuLiquidacion l JOIN l.empleadoRel e WHERE l.codigoLiquidacionPk <> 0";
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion LIKE '%" . $strIdentificacion . "%'";
        }

        $dql .= " ORDER BY l.codigoLiquidacionPk";
        return $dql;
    }  
    
    public function liquidar($codigoLiquidacion) {        
        $em = $this->getEntityManager();
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigoLiquidacion); 
        if($arLiquidacion->getContratoRel()->getFechaUltimoPagoCesantias() <= $arLiquidacion->getContratoRel()->getFechaDesde()) {
            $intDiasCesantias = $arLiquidacion->getContratoRel()->getFechaDesde()->diff($arLiquidacion->getContratoRel()->getFechaHasta());
            $intDiasCesantias = $intDiasCesantias->format('%a');
        }
        if($arLiquidacion->getContratoRel()->getFechaUltimoPagoPrimas() <= $arLiquidacion->getContratoRel()->getFechaDesde()) {
            $intDiasPrimas = $arLiquidacion->getContratoRel()->getFechaDesde()->diff($arLiquidacion->getContratoRel()->getFechaHasta());
            $intDiasPrimas = $intDiasPrimas->format('%a');
        }
        if($arLiquidacion->getContratoRel()->getFechaUltimoPagoVacaciones() <= $arLiquidacion->getContratoRel()->getFechaDesde()) {
            $intDiasVacaciones = $arLiquidacion->getContratoRel()->getFechaDesde()->diff($arLiquidacion->getContratoRel()->getFechaHasta());
            $intDiasVacaciones = $intDiasVacaciones->format('%a');
        }        
        $douIBC = 
        
        $arLiquidacion->setDiasCesantias($intDiasCesantias);
        $arLiquidacion->setDiasPrimas($intDiasPrimas);
        $arLiquidacion->setDiasVacaciones($intDiasVacaciones);
        
        $em->flush();
        return true;
    }        
}