<?php

namespace Brasa\CarteraBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CuentasCobrarRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CarCuentaCobrarRepository extends EntityRepository
{
    /**
     * Aplicar cartera: Este metodo efectua el movimiento de cartera respectivo para un movimiento
     * @param integer $codigoMovimiento Codigo del movimiento
     * */
    /*public function Aplicar($codigoMovimiento) { 
        $em = $this->getEntityManager();
        $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimiento();        
        $arMovimiento = $em->getRepository('BrasaInventarioBundle:InvMovimiento')->find($codigoMovimiento);                
        $arCuentaCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
        $arCuentaCobrar->setFecha($arMovimiento->getFecha());
        $arCuentaCobrar->setMovimientoRel($arMovimiento);
        $arCuentaCobrar->setSoporte($arMovimiento->getNumeroMovimiento());
        $arCuentaCobrar->setTerceroRel($arMovimiento->getTerceroRel());
        $arCuentaCobrar->setValorOriginal($arMovimiento->getTotal());        
        $arCuentaCobrar->setCondicion($arMovimiento->getTerceroRel()->getPlazoPagoCliente());
        $intCondicion = $arMovimiento->getTerceroRel()->getPlazoPagoCliente();
        $dateFechaVence = date("Y/m/d", strtotime(date("m/d/Y")." +$intCondicion day"));
        $arCuentaCobrar->setFechaVence(date_create($dateFechaVence));
        /*
         * Esto determina donde afecta la cuenta por cobrar si en el 
         * debito (Codigo 1) o en credito (Codigo 2)
         * Las facturas van al debito
         * Las NC van al credito
         */      
        /*
        if($arMovimiento->getDocumentoRel()->getTipoAsientoCartera() == 1) {
            $arCuentaCobrar->setDebitos ($arMovimiento->getTotal());
            $arCuentaCobrar->setSaldo ($arMovimiento->getTotal());
        }
            
        
        if($arMovimiento->getDocumentoRel()->getTipoAsientoCartera() == 2)
            $arCuentaCobrar->setCreditos($arMovimiento->getTotal());        
            
        $em->persist($arCuentaCobrar);
        $em->flush();                                  
    }     */
    
    /*public function DevCuentasCobrar ($intCodigoTercero) {
        $em = $this->getEntityManager();         
        $query = $em->createQueryBuilder()            
            ->select('(cc.saldo - cc.creditos) as saldo, cc.valorOriginal, cc.codigoMovimientoFk, cc.fecha, (CURRENT_DATE() - cc.fecha) as nroDias, m.numeroMovimiento, doc.abreviatura, ter.nombreCortoTercero ')                
            ->from('BrasaGeneralBundle:CuentasCobrar', 'cc')
            ->leftJoin('cc.movimientoRel', 'm')
            ->leftJoin('m.documentoRel', 'doc')
            ->leftJoin('cc.terceroRel', 'ter')        
            ->where("cc.codigoTerceroFk = $intCodigoTercero")
            ->getQuery();
        return $query->getResult();        
    }*/       
}