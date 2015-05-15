<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PagosAdicionalesController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $form = $this->createFormBuilder()
            ->add('Generar', 'submit')
            ->getForm();
        $form->handleRequest($request);        
        
        $arCentrosCostos = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentrosCostos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->findAll();                
       
        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $objChkFecha = NULL;
            if (isset($arrControles['ChkFecha']))
                $objChkFecha = $arrControles['ChkFecha'];
            switch ($request->request->get('OpSubmit')) {
                case "OpEliminar";
                    foreach ($arrSeleccionados AS $codigoGuia) {
                        $arGuia = new \Brasa\TransporteBundle\Entity\TteGuia();
                        $arGuia = $em->getRepository('BrasaTransporteBundle:TteGuia')->find($codigoGuia);
                        if($arGuia->getEstadoImpreso() == 0 && $arGuia->getEstadoDespachada() == 0 && $arGuia->getNumeroGuia() == 0) {
                            $em->remove($arGuia);
                            $em->flush();                            
                        }
                    }
                    break;

            }
        } 

        return $this->render('BrasaRecursoHumanoBundle:PagosAdicionales:lista.html.twig', array(
            'arCentrosCostos' => $arCentrosCostos,
            'form' => $form->createView()));
    }       
    
    public function detalleAction($codigoCentroCosto) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $objMensaje = $this->get('mensajes_brasa');        
        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);
        $form = $this->createFormBuilder()
            ->add('BtnRetirarConcepto', 'submit', array('label'  => 'Retirar',))
            ->add('BtnConceptoPermanente', 'submit', array('label'  => 'Permanente',))                
            ->add('BtnRetirarIncapacidad', 'submit', array('label'  => 'Retirar',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnRetirarConcepto')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoPagoAdicional) {
                        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
                        $em->remove($arPagoAdicional);                        
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_detalle', array('codigoCentroCosto' => $codigoCentroCosto)));
                }
            }
            if($form->get('BtnConceptoPermanente')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoPagoAdicional) {
                        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
                        if($arPagoAdicional->getPermanente() == 1) {
                            $arPagoAdicional->setPermanente(0);
                        } else {
                            $arPagoAdicional->setPermanente(1);
                        }
                        $em->persist($arPagoAdicional);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_detalle', array('codigoCentroCosto' => $codigoCentroCosto)));
                }
            }
            if($form->get('BtnRetirarIncapacidad')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarIncapacidad');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoIncapacidad) {
                        $arIncapacidad = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
                        $arIncapacidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->find($codigoIncapacidad);
                        $em->remove($arIncapacidad);                        
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_detalle', array('codigoCentroCosto' => $codigoCentroCosto)));
                }
            }            
        }
        $arPagosAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
        $arPagosAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->findBy(array('codigoCentroCostoFk' => $codigoCentroCosto, 'pagoAplicado' => 0));        
        $arIncapacidades = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
        $arIncapacidades = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->findBy(array('codigoCentroCostoFk' => $codigoCentroCosto));        
        return $this->render('BrasaRecursoHumanoBundle:PagosAdicionales:detalle.html.twig', array(
                    'arCentroCosto' => $arCentroCosto,
                    'arPagosAdicionales' => $arPagosAdicionales,
                    'arIncapacidades' => $arIncapacidades,
                    'form' => $form->createView()
                    ));
    }        
    
    public function generarMasivoListaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->findBy(array('estadoGenerado' => 0));
        
        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);        
        
        $arCentrosCostos = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentrosCostos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->findAll();                
       

        return $this->render('BrasaRecursoHumanoBundle:PagosAdicionales:generarMasivoLista.html.twig', array(
            'arProgramacionPago' => $arProgramacionPago,
            'form' => $form->createView()));
    }           
    
    public function generarMasivoDetalleAction($codigoCentroCosto) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $paginator  = $this->get('knp_paginator');
        
        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);               
        $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->ListaDQL('', $codigoCentroCosto, 1));
        $arEmpleados = $paginator->paginate($query, $request->query->get('page', 1), 50);        
        $form = $this->createFormBuilder()
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrControles = $request->request->All();
            if($form->get('BtnGenerar')->isClicked()) {
                    $intIndice = 0;
                    foreach ($arrControles['LblCodigo'] as $intCodigo) {                        
                        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($intCodigo);
                        if(count($arEmpleado) > 0) {                                                                                        
                            if($arrControles['TxtRNFC'][$intIndice] != "" && $arrControles['TxtRNFC'][$intIndice] != 0) {
                                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(13);
                                $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);
                                $arPagoAdicional->setEmpleadoRel($arEmpleado);
                                $arPagoAdicional->setCentroCostoRel($arCentroCosto);                                    
                                $intHoras = $arrControles['TxtRNFC'][$intIndice];
                                $arPagoAdicional->setCantidad($intHoras);
                                $em->persist($arPagoAdicional);                                
                            }
                            if($arrControles['TxtRNFNC'][$intIndice] != "" && $arrControles['TxtRNFNC'][$intIndice] != 0) {
                                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(7);
                                $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);
                                $arPagoAdicional->setEmpleadoRel($arEmpleado);
                                $arPagoAdicional->setCentroCostoRel($arCentroCosto);                                    
                                $intHoras = $arrControles['TxtRNFNC'][$intIndice];
                                $arPagoAdicional->setCantidad($intHoras);
                                $em->persist($arPagoAdicional);                                
                            }                            
                            if($arrControles['TxtHEFD'][$intIndice] != "" && $arrControles['TxtHEFD'][$intIndice] != 0) {
                                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(12);
                                $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);
                                $arPagoAdicional->setEmpleadoRel($arEmpleado);
                                $arPagoAdicional->setCentroCostoRel($arCentroCosto);                                    
                                $intHoras = $arrControles['TxtHEFD'][$intIndice];
                                $arPagoAdicional->setCantidad($intHoras);
                                $em->persist($arPagoAdicional);                                
                            }                             
                            if($arrControles['TxtHEFN'][$intIndice] != "" && $arrControles['TxtHEFN'][$intIndice] != 0) {
                                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(11);
                                $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);
                                $arPagoAdicional->setEmpleadoRel($arEmpleado);
                                $arPagoAdicional->setCentroCostoRel($arCentroCosto);                                    
                                $intHoras = $arrControles['TxtHEFN'][$intIndice];
                                $arPagoAdicional->setCantidad($intHoras);
                                $em->persist($arPagoAdicional);                                
                            }  
                            if($arrControles['TxtHEOD'][$intIndice] != "" && $arrControles['TxtHEOD'][$intIndice] != 0) {
                                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(6);
                                $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);
                                $arPagoAdicional->setEmpleadoRel($arEmpleado);
                                $arPagoAdicional->setCentroCostoRel($arCentroCosto);                                    
                                $intHoras = $arrControles['TxtHEOD'][$intIndice];
                                $arPagoAdicional->setCantidad($intHoras);
                                $em->persist($arPagoAdicional);                                
                            }                            
                            if($arrControles['TxtHEON'][$intIndice] != "" && $arrControles['TxtHEON'][$intIndice] != 0) {
                                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(10);
                                $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);
                                $arPagoAdicional->setEmpleadoRel($arEmpleado);
                                $arPagoAdicional->setCentroCostoRel($arCentroCosto);                                    
                                $intHoras = $arrControles['TxtHEON'][$intIndice];
                                $arPagoAdicional->setCantidad($intHoras);
                                $em->persist($arPagoAdicional);                                
                            }  
                            if($arrControles['TxtDC'][$intIndice] != "" && $arrControles['TxtDC'][$intIndice] != 0) {
                                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(5);
                                $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);
                                $arPagoAdicional->setEmpleadoRel($arEmpleado);
                                $arPagoAdicional->setCentroCostoRel($arCentroCosto);                                    
                                $intHoras = $arrControles['TxtDC'][$intIndice];
                                $arPagoAdicional->setCantidad($intHoras);
                                $em->persist($arPagoAdicional);                                
                            } 
                            if($arrControles['TxtDNC'][$intIndice] != "" && $arrControles['TxtDNC'][$intIndice] != 0) {
                                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(8);
                                $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);
                                $arPagoAdicional->setEmpleadoRel($arEmpleado);
                                $arPagoAdicional->setCentroCostoRel($arCentroCosto);                                    
                                $intHoras = $arrControles['TxtDNC'][$intIndice];
                                $arPagoAdicional->setCantidad($intHoras);
                                $em->persist($arPagoAdicional);                                
                            }    
                            if($arrControles['TxtRN'][$intIndice] != "" && $arrControles['TxtRN'][$intIndice] != 0) {
                                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(9);
                                $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);
                                $arPagoAdicional->setEmpleadoRel($arEmpleado);
                                $arPagoAdicional->setCentroCostoRel($arCentroCosto);                                    
                                $intHoras = $arrControles['TxtRN'][$intIndice];
                                $arPagoAdicional->setCantidad($intHoras);
                                $em->persist($arPagoAdicional);                                
                            }  
                            if($arrControles['TxtValor'][$intIndice] != "" && $arrControles['TxtValor'][$intIndice] != 0) {
                                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(15);
                                $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);
                                $arPagoAdicional->setEmpleadoRel($arEmpleado);
                                $arPagoAdicional->setCentroCostoRel($arCentroCosto);                                    
                                $intValor = $arrControles['TxtValor'][$intIndice];
                                $arPagoAdicional->setValor($intValor);
                                if($arrControles['TxtDetalle'][$intIndice] != "") {
                                    $arPagoAdicional->setDetalle($arrControles['TxtDetalle'][$intIndice]);
                                }
                                $em->persist($arPagoAdicional);                                
                            }                            
                        }
                        $intIndice++;
                    }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                
            }
        }        
        
        return $this->render('BrasaRecursoHumanoBundle:PagosAdicionales:generarMasivoDetalle.html.twig', array(
            'arEmpleados' => $arEmpleados,
            'form' => $form->createView()
            ));
    }               
}
