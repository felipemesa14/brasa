<?php

namespace Brasa\TransporteBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MovimientosRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TteFacturasRepository extends EntityRepository {

    public function ListaFacturas($boolMostrarAnuladas, $codigoFactura, $numeroFactura, $fechaDesde, $fechaHasta) {        
        $em = $this->getEntityManager();
        $dql   = "SELECT facturas FROM BrasaTransporteBundle:TteFacturas facturas WHERE facturas.codigoFacturaPk <> 0";
        if($boolMostrarAnuladas != 1 ) {
            $dql .= " AND facturas.estadoAnulada = 0";
        }        
        if($codigoFactura != "" ) {
            $dql .= " AND facturas.codigoFacturaPk = " . $codigoFactura;
        }        
        if($numeroFactura != "" ) {
            $dql .= " AND facturas.numeroFactura = " . $numeroFactura;
        }  
        if($fechaDesde != "" ) {
            $dql .= " AND facturas.fecha >= '" . $fechaDesde->format('Y/m/d') . " 00:00:00'";
        }        
        if($fechaHasta != "" ) {
            $dql .= " AND facturas.fecha <= '" . $fechaHasta->format('Y/m/d') . " 23:59:59'";
        }        
        $query = $em->createQuery($dql);        
        return $query;
    }            
}