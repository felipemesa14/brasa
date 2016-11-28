<?php

namespace Brasa\InventarioBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * InvBodegaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InvTerceroRepository extends EntityRepository
{
    public function listaDql($strNit = "", $strNombre = "") {
        $dql   = "SELECT t FROM BrasaInventarioBundle:InvTercero t WHERE t.codigoTerceroPk <> 0";        
        if($strNit != "") {
            $dql .= " AND t.nit = " . $strNit;
        }
        if($strNombre != "") {
            $dql .= " AND t.nombreCorto like '%" . $strNombre. "%'";
        }
        $dql .= " ORDER BY t.nombreCorto ASC";
        return $dql;
    } 
}