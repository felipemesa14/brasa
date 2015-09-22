<?php

namespace Brasa\ContabilidadBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CtbRegistroRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CtbRegistroRepository extends EntityRepository
{

    public function listaDql() {        
        $dql   = "SELECT r FROM BrasaContabilidadBundle:CtbRegistro r WHERE r.codigoRegistroPk <> 0";
        return $dql;
    }           
    
    /**
     * Contabiliza un movimiento de inventario
     * @param integer $codigoMovimiento codigo del movimiento que se va a procesar.
     * */
    public function ContabilizarMovimientoInventario($codigoMovimiento) {
        $em = $this->getEntityManager();
        $strResultado = "";
        $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimiento();
        $arMovimiento = $em->getRepository('BrasaInventarioBundle:InvMovimiento')->find($codigoMovimiento);        
        if($arMovimiento->getEstadoContabilizado() == 0) {
            $strResultado = $this->ValidarContabilizar($codigoMovimiento);                
            
            //REGISTROS GENERALES
            if($strResultado == "") {
                //------------------ Inicio Cuenta de la retencion en la fuente
                if($arMovimiento->getValorRetencionFuente() > 0) {
                    $arRegistro = $this->CrearEncabezadoRegistro($arMovimiento);
                    $arRegistro->setCuentaRel($arMovimiento->getDocumentoRel()->getCuentaRetencionFuenteRel());
                    $arRegistro->setTipoRegistro($arMovimiento->getDocumentoRel()->getTipoCuentaRetencionFuente());
                    $arRegistro->setValor($arMovimiento->getValorRetencionFuente());                                   
                    $arRegistro->setBase($arMovimiento->getTotalBruto());
                    $em->persist($arRegistro);
                    $em->flush();
                }
                //------------------ Fin Cuenta de la retencion en la fuente                

                //------------------ Inicio Cuenta de la retencion CREE
                if($arMovimiento->getValorRetencionCREE() > 0) {                    
                    $arRegistro = $this->CrearEncabezadoRegistro($arMovimiento);
                    $arRegistro->setCuentaRel($arMovimiento->getDocumentoRel()->getCuentaRetencionCREERel());
                    $arRegistro->setTipoRegistro($arMovimiento->getDocumentoRel()->getTipoCuentaRetencionCREE());
                    $arRegistro->setValor($arMovimiento->getValorRetencionCREE());                                       
                    $arRegistro->setBase($arMovimiento->getTotalBruto());
                    $em->persist($arRegistro);
                    $em->flush();
                }
                //------------------ Fin Cuenta de la retencion CREE 
                //
                //------------------ Inicio Cuenta del iva
                if($arMovimiento->getValorTotalIva() > 0) {
                    $arRegistro = $this->CrearEncabezadoRegistro($arMovimiento);                    
                    $arRegistro->setCuentaRel($arMovimiento->getDocumentoRel()->getCuentaIvaRel());
                    $arRegistro->setTipoRegistro($arMovimiento->getDocumentoRel()->getTipoCuentaIva());
                    $arRegistro->setValor($arMovimiento->getValorTotalIva());
                    $arRegistro->setBase($arMovimiento->getTotalBruto());                    

                    $em->persist($arRegistro);
                    $em->flush();
                }
                //------------------ Fin Cuenta del iva                  
                
                //------------------ Inicio Cuenta de cartera/tesoreria
                if($arMovimiento->getDocumentoRel()->getCodigoDocumentoTipoFk() == 1 || $arMovimiento->getDocumentoRel()->getCodigoDocumentoTipoFk() == 4) {
                    $arRegistro = $this->CrearEncabezadoRegistro($arMovimiento);
                    if($arMovimiento->getDocumentoRel()->getCodigoDocumentoTipoFk() == 1) {
                        $arRegistro->setCuentaRel($arMovimiento->getDocumentoRel()->getCuentaTesoreriaRel());
                        $arRegistro->setTipoRegistro($arMovimiento->getDocumentoRel()->getTipoCuentaTesoreria());
                    }
                        
                    if($arMovimiento->getDocumentoRel()->getCodigoDocumentoTipoFk() == 4) {
                        $arRegistro->setCuentaRel($arMovimiento->getDocumentoRel()->getCuentaCartera());                                            
                        $arRegistro->setTipoRegistro($arMovimiento->getDocumentoRel()->getTipoCuentaCartera());
                    }
                    
                    $arRegistro->setValor($arMovimiento->getTotal());                
                    $em->persist($arRegistro);
                    $em->flush();                    
                }
                //------------------ Fin Cuenta de cartera/tesoreria                
            }
             
            
            //DOCUMENTO DE COMPRAS
            if($arMovimiento->getDocumentoRel()->getCodigoDocumentoTipoFk() == 1 && $strResultado == "") {
                $arMovimientosDetalles = new \Brasa\InventarioBundle\Entity\InvMovimientoDetalle();

                //------------------ Inicio Cuenta del Ingreso compras
                //Para las compras
                if($arMovimiento->getDocumentoRel()->getCodigoComprobanteContableFk() == 4)
                    $arMovimientosDetalles = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->DevResumenCuentaCompras($codigoMovimiento);
                //Para las devoluciones de compra
                if($arMovimiento->getDocumentoRel()->getCodigoComprobanteContableFk() == 11)
                    $arMovimientosDetalles = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->DevResumenCuentaDevolucionCompras($codigoMovimiento);


                foreach($arMovimientosDetalles as $arMovimientoDetalle) {
                    $arRegistro = $this->CrearEncabezadoRegistro($arMovimiento);
                    //Para las compras
                    if($arMovimiento->getDocumentoRel()->getCodigoComprobanteContableFk() == 4)
                        $arCuentaContable = $em->getRepository('BrasaContabilidadBundle:CtbCuentaContable')->find($arMovimientoDetalle['cuentaCompras']);

                    //Para las devoluciones de compra
                    if($arMovimiento->getDocumentoRel()->getCodigoComprobanteContableFk() == 11)
                        $arCuentaContable = $em->getRepository('BrasaContabilidadBundle:CtbCuentaContable')->find($arMovimientoDetalle['cuentaDevolucionCompras']);

                    $arRegistro->setCuentaRel($arCuentaContable);
                    $arRegistro->setValor($arMovimientoDetalle['totalBruto']);
                    $arRegistro->setTipoRegistro($arMovimiento->getDocumentoRel()->getTipoCuentaIngreso());
                    $em->persist($arRegistro);
                    $em->flush();
                }
                //------------------ Fin Cuenta del Ingreso compras              
                
                //------------------ Inicio Cuenta de descuentos financieros
                $arMovimientosDescuentosFinancieros = new \Brasa\InventarioBundle\Entity\InvMovimientoDescuentoFinanciero();
                $arMovimientosDescuentosFinancieros = $em->getRepository('BrasaInventarioBundle:InvMovimientoDescuentoFinanciero')->findBy(array('codigoMovimientoFk' => $codigoMovimiento));
                foreach($arMovimientosDescuentosFinancieros as $arMovimientoDescuentoFinanciero) {
                    $arRegistro = $this->CrearEncabezadoRegistro($arMovimiento);                    
                    $arRegistro->setCuentaRel($arMovimientoDescuentoFinanciero->getDescuentoFinancieroRel()->getCuentaRel());                    
                    $arRegistro->setTipoRegistro($arMovimientoDescuentoFinanciero->getDescuentoFinancieroRel()->getTipoRegistro());
                    $arRegistro->setValor($arMovimientoDescuentoFinanciero->getValorTotal());
                    $arRegistro->setBase($arMovimientoDescuentoFinanciero->getBase());                    
                    $em->persist($arRegistro);
                    $em->flush();
                }
                //------------------ Inicio Cuenta de otras retenciones                                
            }

            //DOCUMENTO DE VENTAS
            if($arMovimiento->getDocumentoRel()->getCodigoDocumentoTipoFk() == 4) {
                $arMovimientosDetalles = new \Brasa\InventarioBundle\Entity\InvMovimientoDetalle();
                
                //------------------ Inicio Cuenta del costo en ventas
                $arMovimientosDetalles = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->DevResumenCuentaCostoVentas($codigoMovimiento);
                foreach($arMovimientosDetalles as $arMovimientoDetalle) {
                    $arRegistro = $this->CrearEncabezadoRegistro($arMovimiento);
                    $arCuentaContable = $em->getRepository('BrasaContabilidadBundle:CtbCuentaContable')->find($arMovimientoDetalle['cuentaCosto']);
                    $arRegistro->setCuentaRel($arCuentaContable);                                       
                    $arRegistro->setValor($arMovimientoDetalle['totalCosto']);
                    $arRegistro->setTipoRegistro($arMovimiento->getDocumentoRel()->getTipoCuentaCosto());                    
                    $em->persist($arRegistro);
                    $em->flush();
                }
                //------------------ Fin 

                //------------------ Inicio Cuenta del ingreso ventas
                if($arMovimiento->getDocumentoRel()->getCodigoComprobanteContableFk() == 3)
                    $arMovimientosDetalles = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->DevResumenCuentaVentas($codigoMovimiento);
                //Para las devoluciones de ventas
                if($arMovimiento->getDocumentoRel()->getCodigoComprobanteContableFk() == 13)
                    $arMovimientosDetalles = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->DevResumenCuentaDevolucionVentas($codigoMovimiento);

                foreach($arMovimientosDetalles as $arMovimientoDetalle) {
                    $arRegistro = $this->CrearEncabezadoRegistro($arMovimiento);
                    //Para las ventas
                    if($arMovimiento->getDocumentoRel()->getCodigoComprobanteContableFk() == 3)
                        $arCuentaContable = $em->getRepository('BrasaContabilidadBundle:CtbCuentaContable')->find($arMovimientoDetalle['cuentaVentas']);

                    //Para las devoluciones de venta
                    if($arMovimiento->getDocumentoRel()->getCodigoComprobanteContableFk() == 13)
                        $arCuentaContable = $em->getRepository('BrasaContabilidadBundle:CtbCuentaContable')->find($arMovimientoDetalle['cuentaDevolucionVentas']);

                    $arRegistro->setCuentaRel($arCuentaContable);
                    $arRegistro->setValor($arMovimientoDetalle['totalBruto']);
                    $arRegistro->setTipoRegistro($arMovimiento->getDocumentoRel()->getTipoCuentaIngreso());
                    $em->persist($arRegistro);
                    $em->flush();
                }
                //------------------ Fin Cuenta del ingreso ventas                
                

                //------------------ Inicio Cuenta Inventario
                $arMovimientosDetalles = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->DevResumenCuentaInventario($codigoMovimiento);

                foreach($arMovimientosDetalles as $arMovimientoDetalle) {
                    $arRegistro = $this->CrearEncabezadoRegistro($arMovimiento);
                    //Para las ventas
                    if($arMovimiento->getDocumentoRel()->getCodigoComprobanteContableFk() == 3)
                        $arCuentaContable = $em->getRepository('BrasaContabilidadBundle:CtbCuentaContable')->find($arMovimientoDetalle['cuentaInventario']);                    
                    $arRegistro->setCuentaRel($arCuentaContable);
                    $arRegistro->setValor($arMovimientoDetalle['costo']);
                    $arRegistro->setTipoRegistro($arMovimiento->getDocumentoRel()->getTipoCuentaIngreso());
                    $em->persist($arRegistro);
                    $em->flush();                                        
                }
                //------------------ Fin Cuenta Inventario

                //------------------ Inicio Cuenta de la retencion iva ventas
                if($arMovimiento->getValorRetencionIvaVentas() > 0) {
                    $arRegistro = $this->CrearEncabezadoRegistro($arMovimiento);
                    $arRegistro->setCuentaRel($arMovimiento->getDocumentoRel()->getCuentaRetencionIvaRel());
                    $arRegistro->setTipoRegistro($arMovimiento->getDocumentoRel()->getTipoCuentaRetencionIva());
                    $arRegistro->setValor($arMovimiento->getValorRetencionIvaVentas());                                   
                    $arRegistro->setBase($arMovimiento->getValorTotalIva());
                    $em->persist($arRegistro);
                    $em->flush();
                }
                //------------------ Fin   
                                                              
            }

            //$arMovimiento->setEstadoContabilizado(1);
            //$em->persist($arMovimiento);
            //$em->flush();
        }
        return $strResultado;
    }
    
    private function ValidarContabilizar ($codigoMovimiento) {
        $em = $this->getEntityManager();
        $strResultado = "";
        $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimiento();
        $arMovimiento = $em->getRepository('BrasaInventarioBundle:InvMovimiento')->find($codigoMovimiento);        
        if($arMovimiento->getEstadoContabilizado() == 0) {
            
            //Validaciones generales
            if($arMovimiento->getDocumentoRel()->getCodigoComprobanteContableFk() == "") 
                $strResultado = "- El documento " . $arMovimiento->getCodigoDocumentoFk() . " no tiene comprobante";
            
            
            if($arMovimiento->getValorRetencionFuente() > 0) {                
                if($arMovimiento->getDocumentoRel()->getCodigoCuentaRetencionFuenteFk() == "")
                    $strResultado = "- El documento " . $arMovimiento->getCodigoDocumentoFk() . " no tiene cuenta de retencion en la fuente";
            }

            if($arMovimiento->getValorRetencionCREE() > 0) {                
                if($arMovimiento->getDocumentoRel()->getCodigoCuentaRetencionCREEFk() == "")
                    $strResultado = "- El documento " . $arMovimiento->getCodigoDocumentoFk() . " no tiene cuenta de CREE";
            }
            
            if($arMovimiento->getValorTotalIva() > 0) {                
                if($arMovimiento->getDocumentoRel()->getCodigoCuentaIvaFk() == "")
                    $strResultado = "- El documento " . $arMovimiento->getCodigoDocumentoFk() . " no tiene cuenta de iva";
            }              
            
            if($arMovimiento->getDocumentoRel()->getCodigoDocumentoTipoFk() == 4) {
                if($arMovimiento->getDocumentoRel()->getCodigoCuentaCarteraFk() == "");
                    $strResultado = "- El documento " . $arMovimiento->getCodigoDocumentoFk() . " no tiene cuenta cartera";
            }                
            
            if($arMovimiento->getDocumentoRel()->getCodigoDocumentoTipoFk() == 1) {
                //Para las compras
                if($arMovimiento->getDocumentoRel()->getCodigoComprobanteContableFk() == 4) {
                    $arMovimientosDetalles = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->DevResumenCuentaCompras($codigoMovimiento);                    
                    if(count($arMovimientosDetalles) > 0) {
                        foreach($arMovimientosDetalles as $arMovimientoDetalle) {
                            if($arMovimientoDetalle['cuentaCompras'] == "")
                                $strResultado = $strResultado . "- El mvimiento " . $codigoMovimiento . " la cuenta de compras de algun item esta vacia";
                        }
                    }
                }                
                    
                //Para las devoluciones de compra
                if($arMovimiento->getDocumentoRel()->getCodigoComprobanteContableFk() == 11) {
                    $arMovimientosDetalles = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->DevResumenCuentaDevolucionCompras($codigoMovimiento);
                    if(count($arMovimientosDetalles) > 0) {
                        foreach($arMovimientosDetalles as $arMovimientoDetalle) {
                            if($arMovimientoDetalle['cuentaDevolucionCompras'] == "")
                                $strResultado = $strResultado . "- El mvimiento " . $codigoMovimiento . " la cuenta de devolucion de compras de algun item esta vacia";
                        }
                    }                    
                }
                
                if($arMovimiento->getDocumentoRel()->getCodigoCuentaTesoreriaFk() == "")
                    $strResultado = "- El documento " . $arMovimiento->getCodigoDocumentoFk() . " no tiene cuenta tesoreria";                
            }
        }
        return $strResultado;
    }
    
    private function CrearEncabezadoRegistro ($arMovimiento) {
        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                    
        $arRegistro->setFecha($arMovimiento->getFecha());
        $arRegistro->setNumero($arMovimiento->getNumeroMovimiento());
        $arRegistro->setTerceroRel($arMovimiento->getTerceroRel());
        $arRegistro->setComprobanteContableRel($arMovimiento->getDocumentoRel()->getComprobanteContableRel()); 
        return $arRegistro;        
    }

    public function ContabilizarMovimientoContabilidad($codigoMovimiento) {
        $em = $this->getEntityManager();
        $arMovimiento = new \Brasa\ContabilidadBundle\Entity\CtbMovimiento();
        $arMovimiento = $em->getRepository('BrasaContabilidadBundle:CtbMovimiento')->find($codigoMovimiento);
        if($arMovimiento->getEstadoContabilizado() == 0) {
            $arMovimientosDetalles = new \Brasa\ContabilidadBundle\Entity\CtbMovimientoDetalle();
            $arMovimientosDetalles = $em->getRepository('BrasaContabilidadBundle:CtbMovimientoDetalle')->findBy(array('codigoMovimientoFk' => $codigoMovimiento));
            foreach ($arMovimientosDetalles as $arMovimientosDetalles) {
                $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                    
                $arRegistro->setFecha($arMovimiento->getFecha());
                $arRegistro->setNumero($arMovimiento->getNumeroMovimiento());
                $arRegistro->setTerceroRel($arMovimientosDetalles->getTerceroRel());
                $arRegistro->setComprobanteContableRel($arMovimiento->getComprobanteContableRel());                
                $arRegistro->setCuentaRel($arMovimientosDetalles->getCuentaRel());
                $arRegistro->setCentroCostosRel($arMovimientosDetalles->getCentroCostosRel());
                $arRegistro->setTipoRegistro($arMovimientosDetalles->getTipoRegistro());
                $arRegistro->setValor($arMovimientosDetalles->getValor());
                $arRegistro->setBase($arMovimientosDetalles->getBase());
                $em->persist($arRegistro);
                $em->flush();
            }
            
            //------------------ Inicio Cuenta total --------------------------------
            /*$arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                    
            $arRegistro->setFecha($arMovimiento->getFecha());
            $arRegistro->setNumero($arMovimiento->getNumeroMovimiento());
            $arRegistro->setTerceroRel($arMovimientosDetalles->getTerceroRel());
            $arRegistro->setComprobanteContableRel($arMovimiento->getComprobanteContableRel());                
            $arRegistro->setCuentaRel($arMovimiento->getMovimientoConceptoRel()->getCuentaTotalRel());                                
            $arRegistro->setTipoRegistro($arMovimiento->getMovimientoConceptoRel()->getTipoRegistroTotal());
            $arRegistro->setValor($arMovimiento->getTotalNeto());
            $em->persist($arRegistro);
            $em->flush();*/
            //------------------ Fin Cuenta total --------------------------------------
        }
    }
}