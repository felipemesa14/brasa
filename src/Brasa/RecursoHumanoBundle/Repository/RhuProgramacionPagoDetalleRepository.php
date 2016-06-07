<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuProgramacionPagoDetalleRepository extends EntityRepository {
    
    public function listaDQL($codigoProgramacionPago = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle pd WHERE pd.codigoProgramacionPagoDetallePk <> 0" ;
        if($codigoProgramacionPago != "" ) {
            $dql .= " AND pd.codigoProgramacionPagoFk = " . $codigoProgramacionPago;
        }             

        return $dql;
    }      
    
    public function generarProgramacionPagoDetallePorSede($codigoProgramacionPago) {
        $em = $this->getEntityManager();
        $arProgramacionPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
        $arProgramacionPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
        foreach ($arProgramacionPagoDetalles as $arProgramacionPagoDetalle) {
            $intHoras = 0;
            $arProgramacionPagoDetalleProcesar = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
            $arProgramacionPagoDetalleProcesar = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->find($arProgramacionPagoDetalle->getCodigoProgramacionPagoDetallePk());            
            $arProgramacionPagoDetallesSedes = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalleSede();
            $arProgramacionPagoDetallesSedes = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalleSede')->findBy(array('codigoProgramacionPagoDetalleFk' => $arProgramacionPagoDetalle->getCodigoProgramacionPagoDetallePk(), 'codigoEmpleadoFk' => $arProgramacionPagoDetalle->getCodigoEmpleadoFk()));                        
            foreach ($arProgramacionPagoDetallesSedes as $arProgramacionPagoDetalleSede) {
                $intHoras = $intHoras + $arProgramacionPagoDetalleSede->getHorasPeriodo();
            }
            foreach ($arProgramacionPagoDetallesSedes as $arProgramacionPagoDetalleSede) {
                $arProgramacionPagoDetalleSedeProcesar = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalleSede();
                $arProgramacionPagoDetalleSedeProcesar = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalleSede')->find($arProgramacionPagoDetalleSede->getCodigoProgramacionPagoDetalleSedePk());                                                        
                $arProgramacionPagoDetalleSedeProcesar->setPorcentajeParticipacion(($arProgramacionPagoDetalleSedeProcesar->getHorasPeriodo() / $intHoras) * 100);
                $em->persist($arProgramacionPagoDetalleSedeProcesar);
            }            
            $arProgramacionPagoDetalleProcesar->setHorasPeriodoReales($intHoras);
            $em->persist($arProgramacionPagoDetalleProcesar);
        }
        $em->flush();
    } 
    
    public function listaDQLDetalleArchivo($codigoProgramacionPago = "") {        
        $em = $this->getEntityManager();
        $strSql = "SELECT ppd,e FROM BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle ppd JOIN ppd.empleadoRel e WHERE
                  ppd.codigoProgramacionPagoFk = ".$codigoProgramacionPago."";
    
        //$strSql .= " ORDER BY e.nombreCorto";
        $query = $em->createQuery($strSql);
        $arRegistros = $query->getResult();
        return $arRegistros;        
    }
    
    public function fechaPrimerPago($codigoContrato) {
        $em = $this->getEntityManager();
        $dql   = "SELECT MIN(pp.fechaDesde) FROM BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle ppd JOIN ppd.programacionPagoRel pp "
                . "WHERE ppd.codigoContratoFk = " . $codigoContrato
                . " AND pp.estadoPagado = 1";                
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getSingleScalarResult();
        return $arrayResultado;
    }
    
    public function eliminarTodoEmpleados($codigoProgramacionPago) {
        $em = $this->getEntityManager();
        $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
        $arProgramacionPagoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
        if ($arProgramacionPagoDetalle <> null){
            $strSql = "DELETE FROM rhu_programacion_pago_detalle WHERE codigo_programacion_pago_fk = " . $codigoProgramacionPago;
            $em->getConnection()->executeQuery($strSql);
            //$em->persist($arProgramacionPagoDetalle);
            //$em->flush();
            return true;
        }    
    }
    
}