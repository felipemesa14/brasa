<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuPagoBancoDetalleRepository extends EntityRepository {        
    
    public function listaDetalleDql($codigoPagoBanco) {                
        $dql   = "SELECT pbd FROM BrasaRecursoHumanoBundle:RhuPagoBancoDetalle pbd WHERE pbd.codigoPagoBancoFk = " . $codigoPagoBanco . " ";           
        $dql .= " ORDER BY pbd.codigoBancoFk DESC";
        return $dql;
    }     
    public function listaDQL($strFecha = "") {                
        $dql   = "SELECT pbd FROM BrasaRecursoHumanoBundle:RhuPagoBancoDetalle pbd WHERE pbd.codigoPagoBancoPk <> 0";
        if($strFecha != "") {
            $dql .= " AND pb.fechaAplicacion = '" .$strFecha. "'";
        }    
        
        $dql .= " ORDER BY pb.codigoPagoBancoPk";
        return $dql;
    }     
    public function pendientesContabilizarDql() {        
        $dql   = "SELECT pbd FROM BrasaRecursoHumanoBundle:RhuPagoBancoDetalle pbd WHERE pbd.estadoContabilizado = 0";       
        $dql .= " ORDER BY pbd.codigoPagoBancoDetallePk DESC";
        return $dql;
    } 
    
    public function contabilizadosPagoBancoDql($intPagoDesde = 0, $intPagoHasta = 0) {        
        $em = $this->getEntityManager();
        $dql   = "SELECT pbd,p FROM BrasaRecursoHumanoBundle:RhuPagoBancoDetalle pbd JOIN pbd.pagoRel p WHERE pbd.estadoContabilizado = 1";
        if($intPagoDesde != "" || $intPagoDesde != 0) {
            $dql .= " AND pbd.codigoPagoFk >= " . $intPagoDesde;
        }
        if($intPagoHasta != "" || $intPagoHasta != 0) {
            $dql .= " AND pbd.codigoPagoFk <= " . $intPagoHasta;
        }   
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        return $arrayResultado;
    } 
}