<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuCreditoRepository extends EntityRepository {
    
    public function listaDQL($intNumero = 0, $strCodigoCentroCosto = "", $strIdentificacion = "", $strDesde = "", $strHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT c, e FROM BrasaRecursoHumanoBundle:RhuCredito c JOIN c.empleadoRel e WHERE c.codigoCreditoPk <> 0 AND c.estadoPagado <> 1 AND c.aprobado <> 0";
        if($intNumero != "" && $intNumero != 0) {
            $dql .= " AND c.numero = " . $intNumero;
        }
        if($strCodigoCentroCosto != "") {
            $dql .= " AND e.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }   
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        if ($strDesde != ""){
            $dql .= " AND c.fecha >='" . $strDesde . "'";
        }
        if($strHasta != "") {
            $dql .= " AND c.fecha <='" . $strHasta . "'";
        }
        
        //$dql .= " ORDER BY p.empleadoRel.nombreCorto";
        return $dql;
    }
    
    public function listaCreditoDQL($strCodigoCentroCosto = "", $strIdentificacion = "", $strDesde = "", $strHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT c, e FROM BrasaRecursoHumanoBundle:RhuCredito c JOIN c.empleadoRel e WHERE c.codigoCreditoPk <> 0";
        
        if($strCodigoCentroCosto != "") {
            $dql .= " AND e.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }   
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        if ($strDesde != ""){
            $dql .= " AND c.fecha >='" . $strDesde . "'";
        }
        if($strHasta != "") {
            $dql .= " AND c.fecha <='" . $strHasta . "'";
        }
        
        //$dql .= " ORDER BY p.empleadoRel.nombreCorto";
        return $dql;
    }
    
    public function fechaAntigua() {        
        $em = $this->getEntityManager();
        $dql   = "SELECT min(c.fecha) FROM BrasaRecursoHumanoBundle:RhuCredito c WHERE c.estadoPagado <> 1 AND c.aprobado <> 0";
        $query = $em->createQuery($dql);
        $fechaAntigua = $query->getSingleScalarResult(); 
        return $fechaAntigua;
    }
    
    public function fechaAntiguaCredito() {        
        $em = $this->getEntityManager();
        $dql   = "SELECT min(c.fecha) FROM BrasaRecursoHumanoBundle:RhuCredito c";
        $query = $em->createQuery($dql);
        $fechaAntigua = $query->getSingleScalarResult(); 
        return $fechaAntigua;
    }

    /**
     * Devuelve el total de la cuota de los creditos de un empleado.
     * 
     * @author		Mario Estrada
     * 
     * @param string	Codigo del empleado
     * @return float    Valor de la cuota
     */    
    public function cuotaCreditosNomina($codigoEmpleado) {
        $em = $this->getEntityManager();                
        $dql   = "SELECT SUM(c.vrCuota) FROM BrasaRecursoHumanoBundle:RhuCredito c "
                . "WHERE c.codigoEmpleadoFk = " . $codigoEmpleado . " "
                . "AND c.codigoCreditoTipoPagoFk = 1 "
                . "AND c.estadoPagado = 0 "
                . "AND c.aprobado = 1 "
                . "AND c.estadoSuspendido = 0";
        $query = $em->createQuery($dql);
        $floCuota = $query->getSingleScalarResult();
        return $floCuota;        
    } 
    
    public function listaCreditosTipoVacacion($codigoEmpleado = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT c FROM BrasaRecursoHumanoBundle:RhuCredito c "
                . "WHERE c.estadoPagado = 0"
                . "AND c.codigoCreditoTipoPagoFk = 3"
                . "AND c.aprobado = 1"
                . "AND c.codigoEmpleadoFk = " . $codigoEmpleado;
        $query = $em->createQuery($dql);
        $arCreditosTipoVacacion = $query->getResult();
        return $arCreditosTipoVacacion;
    }
    
    public function TotalCreditosTipoVacacion($codigoEmpleado) {        
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(c.saldo) FROM BrasaRecursoHumanoBundle:RhuCredito c "
                . "WHERE c.estadoPagado = 0"
                . "AND c.codigoCreditoTipoPagoFk = 3"
                . "AND c.aprobado = 1"
                . "AND c.codigoEmpleadoFk = " . $codigoEmpleado;
        $query = $em->createQuery($dql);
        $duoTotal = $query->getSingleScalarResult(); 
        return $duoTotal;
    }
    
}