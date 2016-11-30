<?php

namespace Brasa\InventarioBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MovimientosRetencionesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InvMovimientoDetalleRepository extends EntityRepository
{
    public function numeroRegistros($codigo) {        
        $em = $this->getEntityManager();
        $intNumeroRegistros = 0;
        $dql   = "SELECT COUNT(md.codigoDetalleMovimientoPk) as numeroRegistros FROM BrasaInventarioBundle:InvMovimientoDetalle md "
                . "WHERE md.codigoMovimientoFk = " . $codigo;
        $query = $em->createQuery($dql);
        $arrMovimientoDetalles = $query->getSingleResult(); 
        if($arrMovimientoDetalles) {
            $intNumeroRegistros = $arrMovimientoDetalles['numeroRegistros'];
        }
        return $intNumeroRegistros;
    }
    
    public function consultaKardexDql($strCodigoItem = '') {
        $dql   = "SELECT md FROM BrasaInventarioBundle:InvMovimientoDetalle md WHERE md.codigoDetalleMovimientoPk <> 0 ";        
        if($strCodigoItem != "" ) {
            $dql .= " AND md.codigoItemFk = " . $strCodigoItem;
        }
        $dql .= " ORDER BY md.codigoItemFk";
        return $dql;
    }
    
    public function validarCantidad($codigoMovimiento) {
        $em = $this->getEntityManager();
        $intNumeroRegistros = 0;
        $dql   = "SELECT COUNT(md.codigoDetalleMovimientoPk) as numeroRegistros FROM BrasaInventarioBundle:InvMovimientoDetalle md "
                . "WHERE md.codigoMovimientoFk = " . $codigoMovimiento . " AND md.cantidad <= 0";
        $query = $em->createQuery($dql);
        $arrMovimientoDetalles = $query->getSingleResult(); 
        if($arrMovimientoDetalles) {
            $intNumeroRegistros = $arrMovimientoDetalles['numeroRegistros'];
        }
        return $intNumeroRegistros;     
    }
    
    public function validarLote($codigoMovimiento) {
        $em = $this->getEntityManager();
        $intNumeroRegistros = 0;
        $dql   = "SELECT COUNT(md.codigoDetalleMovimientoPk) as numeroRegistros FROM BrasaInventarioBundle:InvMovimientoDetalle md "
                . "WHERE md.codigoMovimientoFk = " . $codigoMovimiento . " AND md.loteFk is null";
        $query = $em->createQuery($dql);
        $arrMovimientoDetalles = $query->getSingleResult(); 
        if($arrMovimientoDetalles) {
            $intNumeroRegistros = $arrMovimientoDetalles['numeroRegistros'];
        }
        return $intNumeroRegistros;     
    }  
    
    public function validarBodega($codigoMovimiento) {
        $em = $this->getEntityManager();
        $intNumeroRegistros = 0;
        $dql   = "SELECT COUNT(md.codigoDetalleMovimientoPk) as numeroRegistros FROM BrasaInventarioBundle:InvMovimientoDetalle md "
                . "WHERE md.codigoMovimientoFk = " . $codigoMovimiento . " AND md.codigoBodegaFk is null";
        $query = $em->createQuery($dql);
        $arrMovimientoDetalles = $query->getSingleResult(); 
        if($arrMovimientoDetalles) {
            $intNumeroRegistros = $arrMovimientoDetalles['numeroRegistros'];
        } 
        if($intNumeroRegistros <= 0) {
            $dql   = "SELECT md.codigoBodegaFk FROM BrasaInventarioBundle:InvMovimientoDetalle md "
                    . "WHERE md.codigoMovimientoFk = " . $codigoMovimiento;
            $query = $em->createQuery($dql);
            $arrMovimientoDetalles = $query->getResult();             
            foreach ($arrMovimientoDetalles as $arrMovimientoDetalle) {                
                $arBodega = $em->getRepository('BrasaInventarioBundle:InvBodega')->find($arrMovimientoDetalle['codigoBodegaFk']);
                if(!$arBodega) {
                    $intNumeroRegistros = 1;
                }
            }
        }
        return $intNumeroRegistros;     
    }

    public function eliminarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {  
                $arRegistro = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->find($codigo);                                    
                $em->remove($arRegistro);                     
            }                                         
            $em->flush();         
        }
        
    }    
}