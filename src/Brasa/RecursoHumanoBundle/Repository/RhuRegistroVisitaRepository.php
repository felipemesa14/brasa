<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuRegistroVisitaRepository extends EntityRepository {
    
    public function RegistroHoy($strFechaHoy = "") {
        
        $em = $this->getEntityManager();
        $dql   = "SELECT rv FROM BrasaRecursoHumanoBundle:RhuRegistroVisita rv WHERE rv.codigoRegistroVisitaPk <> 0 ";   
        if ($strFechaHoy != ""){
            $dql .= " AND rv.fecha >= '". $strFechaHoy . " 00:00:00' AND rv.fecha <= '" . $strFechaHoy . " 23:59:59' ";
        }
        return $dql;
    }
    
    public function listaDql($strIdentificacion = "", $strNombre = "", $strDesde = "", $strHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT rv FROM BrasaRecursoHumanoBundle:RhuRegistroVisita rv WHERE rv.codigoRegistroVisitaPk <> 0 ";   
        if($strIdentificacion != "") {
            $dql .= " AND rv.numeroIdentificacion = " . $strIdentificacion;
        }   
        if($strNombre != "" ) {
            $dql .= " AND rv.nombre LIKE '%". $strNombre . "%'";
        }
        if ($strDesde != ""){
            $dql .= " AND rv.fecha >= '". date_format($strDesde, 'Y-m-d') . " 00:00:00'";
        }
        if($strHasta != "") {
            $dql .= " AND rv.fecha <= '". date_format($strHasta, 'Y-m-d') . " 23:59:59'";
        }
        $dql .= " ORDER BY rv.fecha";
        return $dql;
    }
    
}