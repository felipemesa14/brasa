<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuIncapacidadRepository extends EntityRepository {
    
    public function listaDQL($intNumero = "", $strCodigoCentroCosto = "", $boolEstadoTranscripcion = "", $strIdentificacion = "", $strNumeroEps = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT i, e FROM BrasaRecursoHumanoBundle:RhuIncapacidad i JOIN i.empleadoRel e WHERE i.codigoIncapacidadPk <> 0";      
        if($intNumero != "") {
            $dql .= " AND i.numero = " . $intNumero;
        } 
        if($strCodigoCentroCosto != "") {
            $dql .= " AND i.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }              
        if($boolEstadoTranscripcion == 1 ) {
            $dql .= " AND i.estadoTranscripcion = 1";
        } elseif($boolEstadoTranscripcion == 0) {
            $dql .= " AND i.estadoTranscripcion = 0";
        }
        if($strNumeroEps != "") {
            $dql .= " AND i.numeroEps = " . $strNumeroEps;
        }         
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion LIKE '%" . $strIdentificacion . "%'";
        } 
        
        //$dql .= " ORDER BY e.nombreCorto";
        return $dql;
    }                    
    
    public function listaIncapacidadesCobrarDQL($strCodigoCentroCosto = "", $strIdentificacion = "", $strDesde = "", $strHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT i, e FROM BrasaRecursoHumanoBundle:RhuIncapacidad i JOIN i.empleadoRel e WHERE i.codigoIncapacidadPk <> 0 AND i.estadoCobrar = 1 AND i.vrSaldo > 0";
        
        if($strCodigoCentroCosto != "") {
            $dql .= " AND i.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }   
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        if ($strDesde != ""){
            $dql .= " AND i.fechaDesde >='" . date_format($strDesde, ('Y-m-d')). "'";
        }
        if($strHasta != "") {
            $dql .= " AND i.fechaHasta <='" . date_format($strHasta, ('Y-m-d')) . "'";
        }
        return $dql;
    }
    
    //lista de incapacidades pendientes por centro centro de costo para el resumen de la programacioan de pago
    public function pendientesCentroCosto($strCodigoCentroCosto) {
        $em = $this->getEntityManager();                
        $dql   = "SELECT i FROM BrasaRecursoHumanoBundle:RhuIncapacidad i "
                . "WHERE i.codigoCentroCostoFk = " . $strCodigoCentroCosto . " "
                . "AND i.cantidadPendiente != 0 ";
        $query = $em->createQuery($dql);
        $arIncapacidadesPendientes = $query->getResult();
        return $arIncapacidadesPendientes;        
    }
    
    //lista de incapacidades pendientes por empleado cuando se esta generando la nomina
    public function pendientesEmpleado($strCodigoEmpleado) {
        $em = $this->getEntityManager();                
        $dql   = "SELECT i FROM BrasaRecursoHumanoBundle:RhuIncapacidad i "
                . "WHERE i.codigoEmpleadoFk = " . $strCodigoEmpleado . " "
                . "AND i.cantidadPendiente != 0 ";
        $query = $em->createQuery($dql);
        $arIncapacidadesPendientesEmpleado = $query->getResult();
        return $arIncapacidadesPendientesEmpleado;        
    }
    
    //lista de incapacidades por cobrar y por entidad de salud
    public function listaIncapacidadesEntidadSaludCobrar($strCodigoEntidadSalud = '') {
        $em = $this->getEntityManager();                
        $dql   = "SELECT i FROM BrasaRecursoHumanoBundle:RhuIncapacidad i "
                . "WHERE i.codigoEntidadSaludFk = " . $strCodigoEntidadSalud . " "
                . "AND i.estadoCobrar = 1 AND i.vrSaldo > 0";
        $query = $em->createQuery($dql);
        $arIncapacidadesCobrar = $query->getResult();
        return $arIncapacidadesCobrar;        
    }
}