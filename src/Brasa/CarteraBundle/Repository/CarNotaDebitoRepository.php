<?php

namespace Brasa\CarteraBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CuentasCobrarRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CarNotaDebitoRepository extends EntityRepository {
    
   public function listaDql($numero, $codigoCliente = "", $boolEstadoImpreso = "") {
        $dql   = "SELECT nd FROM BrasaCarteraBundle:CarNotaDebito nd WHERE nd.codigoNotaDebitoPk <> 0";
        if($numero != "") {
            $dql .= " AND nd.numero = " . $numero;  
        }        
        if($codigoCliente != "") {
            $dql .= " AND nd.codigoClienteFk = " . $codigoCliente;  
        }    
        if($boolEstadoImpreso == 1 ) {
            $dql .= " AND nd.estadoImpreso = 1";
        }
        if($boolEstadoImpreso == "0") {
            $dql .= " AND nd.estadoImpreso = 0";
        }        
        $dql .= " ORDER BY nd.fecha DESC";
        return $dql;
    }
    
   public function listaConsultaDql($numero = "", $codigoCliente = "", $codigoNotaDebitoConcepto = "", $strFechaDesde = "", $strFechaHasta = "") {
        $dql   = "SELECT nd FROM BrasaCarteraBundle:CarNotaDebito nd WHERE nd.codigoNotaDebitoPk <> 0 ";
        if($numero != "") {
            $dql .= " AND nd.numero = " . $numero;  
        }
        if($codigoCliente != "") {
            $dql .= " AND nd.codigoClienteFk = " . $codigoCliente;  
        }
        if($codigoNotaDebitoConcepto != "") {
            $dql .= " AND nd.codigoNotaDebitoConceptoFk = " . $codigoNotaDebitoConcepto;  
        }
        if ($strFechaDesde != ""){
            $dql .= " AND nd.fecha >='" . date_format($strFechaDesde, ('Y-m-d')). "'";
        }
        if($strFechaHasta != "") {
            $dql .= " AND nd.fecha <='" . date_format($strFechaHasta, ('Y-m-d')) . "'";
        }        
        return $dql;
    }  
    
   public function imprimir($codigo) {
        $em = $this->getEntityManager();  
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $strResultado = "";
        $arNotaDebito = new \Brasa\CarteraBundle\Entity\CarNotaDebito();        
        $arNotaDebito = $em->getRepository('BrasaCarteraBundle:CarNotaDebito')->find($codigo);        
        if($arNotaDebito->getEstadoAutorizado() == 1) {
           if($arNotaDebito->getNumero() == 0) {            
                $intNumero = $em->getRepository('BrasaCarteraBundle:CarConsecutivo')->consecutivo(2);
                $arNotaDebito->setNumero($intNumero);
                $arNotaDebito->setEstadoImpreso(1);
                $em->persist($arNotaDebito);
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
                if($em->getRepository('BrasaCarteraBundle:CarNotaDebitoDetalle')->numeroRegistros($codigo) <= 0) {
                    $arNotaDebito = $em->getRepository('BrasaCarteraBundle:CarNotaDebito')->find($codigo);                    
                    if($arNotaDebito->getEstadoAutorizado() == 0) {
                        $em->remove($arNotaDebito);                    
                    }                     
                }               
            }
            $em->flush();
        }
    }       
}