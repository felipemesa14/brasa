<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuCambioSalarioRepository extends EntityRepository {
    
    public function cambiosSalario($codigoContrato = "", $fechaDesde = "", $fechaHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT cs FROM BrasaRecursoHumanoBundle:RhuCambioSalario cs "
                ." WHERE (cs.fecha >= '" . $fechaDesde . "' "
                . "AND cs.fecha <= '" . $fechaHasta . "') "
                . "AND cs.codigoContratoFk = " . $codigoContrato . " "
                . "ORDER BY cs.codigoCambioSalarioPk ASC";
        $query = $em->createQuery($dql);        
        $arCambioSalario = $query->getResult();        
        return $arCambioSalario;
    }  
    
}