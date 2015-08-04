<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuConfiguracionGeneralRepository extends EntityRepository {
    public function configuracionDatoCodigoGeneral($codigoConfiguracion) {
        $em = $this->getEntityManager();
        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionGeneral();
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionGeneral')->find($codigoConfiguracion);
        return $arConfiguracion;
    }
    
}