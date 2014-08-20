<?php

namespace Brasa\InventarioBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ListasCostosDetallesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InvListasCostosDetallesRepository extends EntityRepository
{
    public function DevCosto($codigoTercero, $codigoItem) {
        $em = $this->getEntityManager();   
        $douCosto = 0;
        $arTercero = new \Brasa\GeneralBundle\Entity\GenTerceros();
        $arTercero = $em->getRepository('BrasaGeneralBundle:GenTerceros')->find($codigoTercero);
        $query = $em->createQueryBuilder()            
            ->select('lcd.costoUMM')                
            ->from('BrasaInventarioBundle:InvListasCostosDetalles', 'lcd')
            ->where('lcd.codigoListaCostosFk = :codigoListaCostosFk AND lcd.codigoItemFk = :codigoItemFk')            
            ->setParameter('codigoListaCostosFk', $arTercero->getCodigoListaCostoFk())               
            ->setParameter('codigoItemFk', $codigoItem)
            ->getQuery();
        $arListasCostosDetalles = $query->getResult();
        if(count($arListasCostosDetalles) > 0)
            $douCosto = $arListasCostosDetalles[0]['costoUMM'];        
        return $douCosto;
    }        
}