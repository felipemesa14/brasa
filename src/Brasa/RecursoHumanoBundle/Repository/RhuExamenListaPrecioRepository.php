<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuExamenListaPrecioRepository extends EntityRepository {

    public function devuelvePrecio($codigoEntidadExamen, $codigoExamenTipo) {
        $em = $this->getEntityManager();
        $douPrecio = 0;
        $dql   = "SELECT s FROM BrasaRecursoHumanoBundle:RhuExamenListaPrecio s WHERE s.codigoEntidadExamenFk = " .$codigoEntidadExamen. " AND s.codigoExamenTipoFk = ". $codigoExamenTipo ;
        $query = $em->createQuery($dql);
        $arExamenListaPrecio = $query->getResult();
        if(count($arExamenListaPrecio) > 0) {
            $douPrecio = $arExamenListaPrecio[0]->getPrecio();
        }
        return $douPrecio;
    }
}