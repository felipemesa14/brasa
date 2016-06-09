<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuSsoAporteRepository extends EntityRepository {
    
    public function listaDQL($codigoPeriodoDetalle, $strCodigoCentroCosto) {                    
            $dql   = "SELECT a, e FROM BrasaRecursoHumanoBundle:RhuSsoAporte a JOIN a.empleadoRel e WHERE a.codigoPeriodoDetalleFk = " . $codigoPeriodoDetalle;
            if($strCodigoCentroCosto != "") {
                $dql .= " AND e.codigoCentroCostoFk = " . $strCodigoCentroCosto;
            }
            return $dql;
    }
    
    public function listaAportesDQL($strIdentificacion = "", $strDesde = "", $strHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT ssoa, ssoap, e FROM BrasaRecursoHumanoBundle:RhuSsoAporte ssoa JOIN ssoa.ssoPeriodoRel ssoap JOIN ssoa.empleadoRel e WHERE ssoa.codigoAportePk <> 0 ";   
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        if ($strDesde != ""){
            $dql .= " AND ssoap.fechaDesde >='" . date_format($strDesde, ('Y-m-d')). "'";
        }
        if($strHasta != "") {
            $dql .= " AND ssoap.fechaHasta <='" . date_format($strHasta, ('Y-m-d')) . "'";
        }
        return $dql;
    }
    
    public function devuelveCostosParafiscales($fechaDesde = "", $fechaHasta = "", $fechaProceso = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT a, ap, c FROM BrasaRecursoHumanoBundle:RhuSsoAporte a JOIN a.ssoPeriodoRel ap JOIN a.contratoRel c WHERE a.codigoAportePk <> 0"
                . "AND ap.fechaDesde >= '" . $fechaDesde . "' AND ap.fechaDesde <= '" . $fechaHasta . "'";
                if ($fechaProceso != ""){
                    $dql .= " AND ap.fechaDesde LIKE '%".$fechaProceso. "%' AND ap.fechaHasta LIKE '%".$fechaProceso. "%'";
                }
                
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        return $arrayResultado;
    }
    
    //Esta funcion se utiliza en la utilidad de supervigilancia - parafiscales         
    public function parafiscalesSupervigilancia($fechaDesde, $fechaHasta) {
        $em = $this->getEntityManager();
        $dql   = "SELECT a.mes, c.nombre, COUNT(a.codigoAportePk) as numeroEmpleados, SUM(a.cotizacionSalud) as eps, SUM(a.cotizacionPension) as pension, SUM(a.cotizacionRiesgos) as arl, SUM(a.cotizacionCaja) as ccf, SUM(a.cotizacionSena) as sena, SUM(a.cotizacionIcbf) as icbf, SUM(a.ibcPension) as nomina FROM BrasaRecursoHumanoBundle:RhuSsoAporte a JOIN a.empleadoRel e JOIN a.cargoRel c "
                . "WHERE a.fechaDesde >= '" . $fechaDesde . "' AND a.fechaHasta <= '" . $fechaHasta . "' "
                . "GROUP BY a.mes, a.codigoCargoFk";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        return $arrayResultado;
    } 
    
}