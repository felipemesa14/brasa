<?php

namespace Brasa\TurnoBundle\Controller\Buscar;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

class ClienteController extends Controller
{
    var $strDqlLista = "";     
    var $strCodigo = "";
    var $strNombre = "";
    
    /**
     * @Route("/tur/burcar/cliente/{campoCodigo}/{campoNombre}", name="brs_tur_buscar_cliente")
     */      
    public function listaAction($campoCodigo,$campoNombre) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->lista();
            }
        }
        $arCliente = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Buscar:cliente.html.twig', array(
            'arClientes' => $arCliente,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
            ));
    }        
    
    private function lista() {        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurCliente')->listaDQL(
                $this->strNombre,                
                $this->strCodigo   
                ); 
    }       
    
    private function formularioLista() {                
        $form = $this->createFormBuilder()                                                
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $this->strNombre))
            ->add('TxtCodigo', 'text', array('label'  => 'Codigo','data' => $this->strCodigo))                            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();        
        return $form;
    }           

    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $this->strNombre = $form->get('TxtNombre')->getData();
    }    
          
}