<?php

namespace Brasa\CarteraBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CuentasCobrarRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CarNotaCreditoRepository extends EntityRepository {
    
   public function listaDql($numero, $codigoCliente = "", $boolEstadoImpreso = "") {
        $dql   = "SELECT nc FROM BrasaCarteraBundle:CarNotaCredito nc WHERE nc.codigoNotaCreditoPk <> 0";
        if($numero != "") {
            $dql .= " AND nc.numero = " . $numero;  
        }        
        if($codigoCliente != "") {
            $dql .= " AND nc.codigoClienteFk = " . $codigoCliente;  
        }    
        if($boolEstadoImpreso == 1 ) {
            $dql .= " AND nc.estadoImpreso = 1";
        }
        if($boolEstadoImpreso == "0") {
            $dql .= " AND nc.estadoImpreso = 0";
        }        
        $dql .= " ORDER BY nc.fecha DESC";
        return $dql;
    }
    
   public function listaConsultaDql($numero = "", $codigoCliente = "", $codigoNotaCreditoConcepto = "", $strFechaDesde = "", $strFechaHasta = "") {
        $dql   = "SELECT nc FROM BrasaCarteraBundle:CarNotaCredito nc WHERE nc.codigoNotaCreditoPk <> 0 ";
        if($numero != "") {
            $dql .= " AND nc.numero = " . $numero;  
        }
        if($codigoCliente != "") {
            $dql .= " AND nc.codigoClienteFk = " . $codigoCliente;  
        }
        if($codigoNotaCreditoConcepto != "") {
            $dql .= " AND nc.codigoNotaCreditoConceptoFk = " . $codigoNotaCreditoConcepto;  
        }
        if ($strFechaDesde != ""){
            $dql .= " AND nc.fecha >='" . date_format($strFechaDesde, ('Y-m-d')). "'";
        }
        if($strFechaHasta != "") {
            $dql .= " AND nc.fecha <='" . date_format($strFechaHasta, ('Y-m-d')) . "'";
        }        
        return $dql;
    }  
    
   public function imprimir($codigo) {
        $em = $this->getEntityManager();  
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $strResultado = "";
        $arNotaCredito = new \Brasa\CarteraBundle\Entity\CarNotaCredito();        
        $arNotaCredito = $em->getRepository('BrasaCarteraBundle:CarNotaCredito')->find($codigo);        
        if($arNotaCredito->getEstadoAutorizado() == 1) {
           if($arNotaCredito->getNumero() == 0) {            
                $intNumero = $em->getRepository('BrasaCarteraBundle:CarConsecutivo')->consecutivo(2);
                $arNotaCredito->setNumero($intNumero);
                $arNotaCredito->setEstadoImpreso(1);
                $em->persist($arNotaCredito);
                $em->flush();
            } 
        } else {
            $strResultado = "Debe autorizar la cotizacion para imprimirla";
        }
        return $strResultado;
    }    
    
   public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {                
                if($em->getRepository('BrasaCarteraBundle:CarNotaCreditoDetalle')->numeroRegistros($codigo) <= 0) {
                    $arNotaCredito = $em->getRepository('BrasaCarteraBundle:CarNotaCredito')->find($codigo);                    
                    if($arNotaCredito->getEstadoAutorizado() == 0) {
                        $em->remove($arNotaCredito);                    
                    }                     
                }               
            }
            $em->flush();
        }
    }
           
}