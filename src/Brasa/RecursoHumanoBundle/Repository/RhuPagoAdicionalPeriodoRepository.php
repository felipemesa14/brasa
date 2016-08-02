<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuPagoAdicionalPeriodoRepository extends EntityRepository {
    
    public function listaDql() {
        $em = $this->getEntityManager();
        $dql   = "SELECT pap FROM BrasaRecursoHumanoBundle:RhuPagoAdicionalPeriodo pap WHERE pap.codigoPagoAdicionalPeriodoPk <> 0 ";
        $dql .= " ORDER BY pap.codigoPagoAdicionalPeriodoPk DESC";
        return $dql;
    } 

}