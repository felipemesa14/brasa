<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class ConsultasController extends Controller
{
    var $strSqlLista = "";
    var $strSqlCreditoLista = "";
    var $strSqlServiciosPorCobrarLista = "";
    var $strSqlProgramacionesPagoLista = "";
    var $strSqlIncapacidadesCobrarLista = "";
    public function costosGeneralAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listarCostosGeneral();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listarCostosGeneral();
                $this->generarExcel();
            }
            if($form->get('BtnPDF')->isClicked()) {
                $this->filtrarLista($form);
                $this->listarCostosGeneral();
                $objReporteCostos = new \Brasa\RecursoHumanoBundle\Reportes\ReporteCostos();
                $objReporteCostos->Generar($this, $this->strSqlLista);
            }            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listarCostosGeneral();
            }

        }
        $arPagos = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Costos:general.html.twig', array(
            'arPagos' => $arPagos,
            'form' => $form->createView()
            ));
    }
    
    public function creditosGeneralAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioCreditosLista();
        $form->handleRequest($request);
        $this->CreditosListar();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcelCredito')->isClicked()) {
                $this->filtrarCreditoLista($form);
                $this->CreditosListar();
                $this->generarCreditoExcel();
            }
            if($form->get('BtnPDFCredito')->isClicked()) {
                $this->filtrarCreditoLista($form);
                $this->CreditosListar();
                $objReporteCreditos = new \Brasa\RecursoHumanoBundle\Reportes\ReporteCreditos();
                $objReporteCreditos->Generar($this, $this->strSqlCreditoLista);
            }            
            if($form->get('BtnFiltrarCredito')->isClicked()) {
                $this->filtrarCreditoLista($form);
                $this->CreditosListar();
            }

        }
        $arCreditos = $paginator->paginate($em->createQuery($this->strSqlCreditoLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Creditos:general.html.twig', array(
            'arCreditos' => $arCreditos,
            'form' => $form->createView()
            ));
    }   
    
    public function programacionesPagoAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioProgramacionesPagoLista();
        $form->handleRequest($request);
        $this->ProgramacionesPagoListar();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcelProgramacionesPago')->isClicked()) {
                $this->filtrarProgramacionesPagoLista($form);
                $this->ProgramacionesPagoListar();
                $this->generarProgramacionesPagoExcel();
            }
            if($form->get('BtnPDFProgramacionesPago')->isClicked()) {
                $this->filtrarProgramacionesPagoLista($form);
                $this->ProgramacionesPagoListar();
                $objReporteProgramacionesPago = new \Brasa\RecursoHumanoBundle\Reportes\ReporteProgramacionesPago();
                $objReporteProgramacionesPago->Generar($this, $this->strSqlProgramacionesPagoLista);
                
            }            
            if($form->get('BtnFiltrarProgramacionesPago')->isClicked()) {
                $this->filtrarProgramacionesPagoLista($form);
                $this->ProgramacionesPagoListar();
            }

        }
        $arPagos = $paginator->paginate($em->createQuery($this->strSqlProgramacionesPagoLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/ProgramacionesPagos:ProgramacionesPago.html.twig', array(
            'arPagos' => $arPagos,
            'form' => $form->createView()
            ));
    }
    
    public function serviciosCobrarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioServiciosPorCobrarLista();
        $form->handleRequest($request);
        $this->ServiciosPorCobrarListar();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcelServiciosPorCobrar')->isClicked()) {
                $this->filtrarServiciosPorCobrarLista($form);
                $this->ServiciosPorCobrarListar();
                $this->generarServiciosPorCobrarExcel();
            }
            if($form->get('BtnPDFServiciosPorCobrar')->isClicked()) {
                $this->filtrarServiciosPorCobrarLista($form);
                $this->ServiciosPorCobrarListar();
                $objReporteServiciosPorCobrar = new \Brasa\RecursoHumanoBundle\Reportes\ReporteServiciosPorCobrar();
                $objReporteServiciosPorCobrar->Generar($this, $this->strSqlServiciosPorCobrarLista);
            }            
            if($form->get('BtnFiltrarServiciosPorCobrar')->isClicked()) {
                $this->filtrarServiciosPorCobrarLista($form);
                $this->ServiciosPorCobrarListar();
            }

        }
        $arServiciosPorCobrar = $paginator->paginate($em->createQuery($this->strSqlServiciosPorCobrarLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Servicios:porCobrar.html.twig', array(
            'arServiciosPorCobrar' => $arServiciosPorCobrar,
            'form' => $form->createView()
            ));
    }
    
    public function IncapacidadesCobrarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioincapacidadesCobrarLista();
        $form->handleRequest($request);
        $this->IncapacidadesCobrarListar();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcelIncapacidadesCobrar')->isClicked()) {
                $this->filtrarIncapacidadesCobrarLista($form);
                $this->IncapacidadesCobrarListar();
                $this->generarIncapacidadesCobrarExcel();
            }
            if($form->get('BtnPDFIncapacidadesCobrar')->isClicked()) {
                $this->filtrarIncapacidadesCobrarLista($form);
                $this->IncapacidadesCobrarListar();
                $objReporteIncapacidadesCobrar = new \Brasa\RecursoHumanoBundle\Reportes\ReporteIncapacidadesCobrar();
                $objReporteIncapacidadesCobrar->Generar($this, $this->strSqlIncapacidadesCobrarLista);
            }            
            if($form->get('BtnFiltrarIncapacidadesCobrar')->isClicked()) {
                $this->filtrarIncapacidadesCobrarLista($form);
                $this->IncapacidadesCobrarListar();
            }

        }
        $arIncapacidadesCobrar = $paginator->paginate($em->createQuery($this->strSqlIncapacidadesCobrarLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/IncapacidadesCobrar:Incapacidades.html.twig', array(
            'arIncapacidadesCobrar' => $arIncapacidadesCobrar,
            'form' => $form->createView()
            ));
    }
    
    private function listarCostosGeneral() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->listaDqlCostos(
                    "",
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion')
                    );
    }
    
    private function CreditosListar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlCreditoLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->listaDQL(
                    "",
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                    );
    } 
    
    private function ServiciosPorCobrarListar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlServiciosPorCobrarLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuServicioCobrar')->listaServiciosPorCobrarDQL(
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                    );
    }
    
    private function ProgramacionesPagoListar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlProgramacionesPagoLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->listaConsultaPagosDQL(
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta'),
                    $session->get('filtroCodigoPago')
                    );
    }
    
    private function IncapacidadesCobrarListar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlIncapacidadesCobrarLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->listaIncapacidadesCobrarDQL(
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                    );
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde', 'date', array('required' => true, 'widget' => 'single_text'))
            ->add('fechaHasta', 'date', array('required' => true, 'widget' => 'single_text'))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnPDF', 'submit', array('label'  => 'PDF',))
            ->getForm();
        return $form;
    }
    
    private function formularioCreditosLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        $fechaAntigua = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->fechaAntigua();
        
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrarCredito', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcelCredito', 'submit', array('label'  => 'Excel',))
            ->add('BtnPDFCredito', 'submit', array('label'  => 'PDF',))
            ->getForm();
        return $form;
    }
    
    private function formularioProgramacionesPagoLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))    
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('codigoPago', 'text', array('label'  => 'codigoPago'))
            ->add('BtnFiltrarProgramacionesPago', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcelProgramacionesPago', 'submit', array('label'  => 'Excel',))
            ->add('BtnPDFProgramacionesPago', 'submit', array('label'  => 'PDF',))
            ->getForm();
        return $form;
    }
    
    private function formularioServiciosPorCobrarLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrarServiciosPorCobrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcelServiciosPorCobrar', 'submit', array('label'  => 'Excel',))
            ->add('BtnPDFServiciosPorCobrar', 'submit', array('label'  => 'PDF',))
            ->getForm();
        return $form;
    }
    
    private function formularioIncapacidadesCobrarLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))    
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrarIncapacidadesCobrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcelIncapacidadesCobrar', 'submit', array('label'  => 'Excel',))
            ->add('BtnPDFIncapacidadesCobrar', 'submit', array('label'  => 'PDF',))
            ->getForm();
        return $form;
    }

    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
    }
    
    private function filtrarCreditoLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
    }
    
    private function filtrarProgramacionesPagoLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
        $session->set('filtroCodigoPago', $form->get('codigoPago')->getData());
    }
    
    private function filtrarIncapacidadesCobrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
    }
    
    private function filtrarServiciosPorCobrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
    }

    private function generarExcel() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'DESDE')
                    ->setCellValue('C1', 'HASTA')
                    ->setCellValue('D1', 'IDENTIFICACION')
                    ->setCellValue('E1', 'NOMBRE')
                    ->setCellValue('F1', 'CENTRO COSTOS')
                    ->setCellValue('G1', 'BASICO')
                    ->setCellValue('H1', 'TIEMPO EXTRA')
                    ->setCellValue('I1', 'VALORES ADICIONALES')
                    ->setCellValue('J1', 'AUX. TRANSPORTE')
                    ->setCellValue('K1', 'ARP')
                    ->setCellValue('L1', 'EPS')
                    ->setCellValue('M1', 'PENSION')
                    ->setCellValue('N1', 'CAJA')
                    ->setCellValue('O1', 'ICBF')
                    ->setCellValue('P1', 'SENA')
                    ->setCellValue('Q1', 'CESANTIAS')
                    ->setCellValue('R1', 'VACACIONES')
                    ->setCellValue('S1', 'ADMON')
                    ->setCellValue('T1', 'COSTO')
                    ->setCellValue('U1', 'TOTAL')
                    ->setCellValue('W1', 'NETO')
                    ->setCellValue('X1', 'IBC')
                    ->setCellValue('Y1', 'AUX. TRANSPORTE COTIZACION')
                    ->setCellValue('Z1', 'DIAS PERIODO')
                    ->setCellValue('AA1', 'SALARIO PERIODO')
                    ->setCellValue('AB1', 'SALARIO EMPLEADO');

        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $query->getResult();
        foreach ($arPagos as $arPago) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPago->getCodigoPagoPk())
                    ->setCellValue('B' . $i, $arPago->getFechaDesde()->Format('Y-m-d'))
                    ->setCellValue('C' . $i, $arPago->getFechaHasta()->Format('Y-m-d'))
                    ->setCellValue('D' . $i, $arPago->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arPago->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arPago->getCentroCostoRel()->getNombre())
                    ->setCellValue('G' . $i, $arPago->getVrSalario())
                    ->setCellValue('H' . $i, $arPago->getVrAdicionalTiempo())
                    ->setCellValue('I' . $i, $arPago->getVrAdicionalValor())
                    ->setCellValue('J' . $i, $arPago->getVrAuxilioTransporte())
                    ->setCellValue('K' . $i, $arPago->getVrArp())
                    ->setCellValue('L' . $i, $arPago->getVrEps())
                    ->setCellValue('M' . $i, $arPago->getVrPension())
                    ->setCellValue('N' . $i, $arPago->getVrCaja())
                    ->setCellValue('O' . $i, $arPago->getVrIcbf())
                    ->setCellValue('P' . $i, $arPago->getVrSena())
                    ->setCellValue('Q' . $i, $arPago->getVrCesantias())
                    ->setCellValue('R' . $i, $arPago->getVrVacaciones())
                    ->setCellValue('S' . $i, $arPago->getVrAdministracion())
                    ->setCellValue('T' . $i, $arPago->getVrCosto())
                    ->setCellValue('U' . $i, $arPago->getVrTotalCobrar())
                    ->setCellValue('W' . $i, $arPago->getVrNeto())
                    ->setCellValue('X' . $i, $arPago->getVrIngresoBaseCotizacion())
                    ->setCellValue('Y' . $i, $arPago->getVrAuxilioTransporteCotizacion())
                    ->setCellValue('Z' . $i, $arPago->getDiasPeriodo())
                    ->setCellValue('AA' . $i, $arPago->getVrSalarioPeriodo())
                    ->setCellValue('AB' . $i, $arPago->getVrSalarioEmpleado());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('costos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Costos.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }
    
    private function generarCreditoExcel() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'TIPO')
                    ->setCellValue('C1', 'FECHA')
                    ->setCellValue('D1', 'CENTRO COSTOS')
                    ->setCellValue('E1', 'IDENTIFICACION')
                    ->setCellValue('F1', 'NOMBRE')
                    ->setCellValue('G1', 'VR. CREDITO')
                    ->setCellValue('H1', 'VR. CUOTA')
                    ->setCellValue('I1', 'VR. SALDO')
                    ->setCellValue('J1', 'CUOTAS')
                    ->setCellValue('K1', 'CUOTA ACTUAL')
                    ->setCellValue('L1', 'APROBADO')
                    ->setCellValue('M1', 'SUSPENDIDO');

        $i = 2;
        $query = $em->createQuery($this->strSqlCreditoLista);
        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCreditos = $query->getResult();
        foreach ($arCreditos as $arCredito) {
            if ($arCredito->getAprobado() == 1) {
                $Aprobado = "SI";
            }
            if ($arCredito->getEstadoSuspendido() == 1) {
                $Suspendido = "SI";
            }
            else {
                $Suspendido = "NO";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCredito->getCodigoCreditoPk())
                    ->setCellValue('B' . $i, $arCredito->getCreditoTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arCredito->getFecha()->Format('Y-m-d'))
                    ->setCellValue('D' . $i, $arCredito->getEmpleadoRel()->getCentroCostoRel()->getNombre())
                    ->setCellValue('E' . $i, $arCredito->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('F' . $i, $arCredito->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('G' . $i, $arCredito->getVrPagar())
                    ->setCellValue('H' . $i, $arCredito->getVrCuota())
                    ->setCellValue('I' . $i, $arCredito->getSaldo())
                    ->setCellValue('J' . $i, $arCredito->getNumeroCuotas())
                    ->setCellValue('K' . $i, $arCredito->getNumeroCuotaActual())
                    ->setCellValue('L' . $i, $Aprobado)
                    ->setCellValue('M' . $i, $Suspendido);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('creditos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ConsultaCreditos.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }
    
    private function generarServiciosPorCobrarExcel() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'CENTRO COSTOS')
                    ->setCellValue('C1', 'IDENTIFICACION')
                    ->setCellValue('D1', 'EMPLEADO')
                    ->setCellValue('E1', 'DESDE')
                    ->setCellValue('F1', 'HASTA')
                    ->setCellValue('G1', 'VR. SALARIO')
                    ->setCellValue('H1', 'VR. SALARIO PERIODO')
                    ->setCellValue('I1', 'VR. SALARIO EMPLEADO')
                    ->setCellValue('J1', 'VR. DEVENGADO')
                    ->setCellValue('K1', 'VR. DEDUCCIONES')
                    ->setCellValue('L1', 'VR. ADICIONAL TIEMPO')
                    ->setCellValue('M1', 'VR. ADICIONAL VALOR')
                    ->setCellValue('N1', 'VR. AUXILIO TRANSPORTE')
                    ->setCellValue('O1', 'VR. AUXILIO TRANSPORTE COTIZACION')
                    ->setCellValue('P1', 'VR. ARP')
                    ->setCellValue('Q1', 'VR. EPS')
                    ->setCellValue('R1', 'VR. PENSION')
                    ->setCellValue('S1', 'VR. CAJA COMPENSACION')
                    ->setCellValue('T1', 'VR. SENA')
                    ->setCellValue('U1', 'VR. ICBF')
                    ->setCellValue('V1', 'VR. CESANTIAS')
                    ->setCellValue('W1', 'VR. VACACIONES')
                    ->setCellValue('X1', 'VR. ADMINISTRACION')
                    ->setCellValue('Y1', 'VR. NETO')
                    ->setCellValue('Z1', 'VR. BRUTO')
                    ->setCellValue('AA1', 'VR. TOTAL COBRAR')
                    ->setCellValue('AB1', 'VR. COSTO')
                    ->setCellValue('AC1', 'VR. INGRESO BASE COTIZACION')
                    ->setCellValue('AD1', 'ESTADO COBRADO')
                    ->setCellValue('AE1', 'DIAS PERIODO');

        $i = 2;
        $query = $em->createQuery($this->strSqlServiciosPorCobrarLista);
        $arServiciosPorCobrar = new \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar();
        $arServiciosPorCobrar = $query->getResult();
        foreach ($arServiciosPorCobrar as $arServiciosPorCobrar) {
            if ($arServiciosPorCobrar->getEstadoCobrado() == 1) {
                $estado = "SI";
            } else {
                $estado = "NO";
            }
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arServiciosPorCobrar->getCodigoServicioCobrarPk())
                    ->setCellValue('B' . $i, $arServiciosPorCobrar->getCentroCostoRel()->getNombre())
                    ->setCellValue('C' . $i, $arServiciosPorCobrar->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arServiciosPorCobrar->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $arServiciosPorCobrar->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('F' . $i, $arServiciosPorCobrar->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('G' . $i, $arServiciosPorCobrar->getVrSalario())
                    ->setCellValue('H' . $i, $arServiciosPorCobrar->getVrSalarioPeriodo())
                    ->setCellValue('I' . $i, $arServiciosPorCobrar->getVrSalarioEmpleado())
                    ->setCellValue('J' . $i, $arServiciosPorCobrar->getVrDevengado())
                    ->setCellValue('K' . $i, $arServiciosPorCobrar->getVrDeducciones())
                    ->setCellValue('L' . $i, $arServiciosPorCobrar->getVrAdicionalTiempo())
                    ->setCellValue('M' . $i, $arServiciosPorCobrar->getVrAdicionalValor())
                    ->setCellValue('N' . $i, $arServiciosPorCobrar->getVrAuxilioTransporte())
                    ->setCellValue('O' . $i, $arServiciosPorCobrar->getVrAuxilioTransporteCotizacion())
                    ->setCellValue('P' . $i, $arServiciosPorCobrar->getVrArp())
                    ->setCellValue('Q' . $i, $arServiciosPorCobrar->getVrEps())
                    ->setCellValue('R' . $i, $arServiciosPorCobrar->getVrPension())
                    ->setCellValue('S' . $i, $arServiciosPorCobrar->getVrCaja())
                    ->setCellValue('T' . $i, $arServiciosPorCobrar->getVrSena())
                    ->setCellValue('U' . $i, $arServiciosPorCobrar->getVrIcbf())
                    ->setCellValue('V' . $i, $arServiciosPorCobrar->getVrCesantias())
                    ->setCellValue('W' . $i, $arServiciosPorCobrar->getVrVacaciones())
                    ->setCellValue('X' . $i, $arServiciosPorCobrar->getVrAdministracion())
                    ->setCellValue('Y' . $i, $arServiciosPorCobrar->getVrNeto())
                    ->setCellValue('Z' . $i, $arServiciosPorCobrar->getVrBruto())
                    ->setCellValue('AA' . $i, $arServiciosPorCobrar->getVrTotalCobrar())
                    ->setCellValue('AB' . $i, $arServiciosPorCobrar->getVrCosto())
                    ->setCellValue('AC' . $i, $arServiciosPorCobrar->getVrIngresoBaseCotizacion())
                    ->setCellValue('AD' . $i, $estado)
                    ->setCellValue('AE' . $i, $arServiciosPorCobrar->getDiasPeriodo());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('ServiciosPorCobrar');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ConsultaServiciosPorCobrar.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }
    
    private function generarProgramacionesPagoExcel() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'NÚMERO')
                    ->setCellValue('C1', 'TIPO')
                    ->setCellValue('D1', 'IDENTIFICACIÓN')
                    ->setCellValue('E1', 'EMPLEADO')
                    ->setCellValue('F1', 'CENTRO COSTOS')
                    ->setCellValue('G1', 'PERIODO DESDE')
                    ->setCellValue('H1', 'DESDE')
                    ->setCellValue('I1', 'HASTA')
                    ->setCellValue('J1', 'VR. SALARIO ')
                    ->setCellValue('K1', 'VR. DEVENGADO')
                    ->setCellValue('L1', 'VR. DEDUCCIONES')
                    ->setCellValue('M1', 'VR. NETO');

        $i = 2;
        $query = $em->createQuery($this->strSqlProgramacionesPagoLista);
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $query->getResult();
        foreach ($arPagos as $arPago) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPago->getCodigoPagoPk())
                    ->setCellValue('B' . $i, $arPago->getNumero())
                    ->setCellValue('C' . $i, $arPago->getPagoTipoRel()->getNombre())
                    ->setCellValue('D' . $i, $arPago->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arPago->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arPago->getCentroCostoRel()->getNombre())
                    ->setCellValue('G' . $i, $arPago->getFechaDesdePago()->format('Y/m/d'))
                    ->setCellValue('H' . $i, $arPago->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('I' . $i, $arPago->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('J' . $i, $arPago->getVrSalario())
                    ->setCellValue('K' . $i, $arPago->getVrDevengado())
                    ->setCellValue('L' . $i, $arPago->getVrDeducciones())
                    ->setCellValue('M' . $i, $arPago->getVrNeto());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('ProgramacionesPagos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteProgramacionesPagos.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }
    
    private function generarIncapacidadesCobrarExcel() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'TIPO')
                    ->setCellValue('C1', 'DIAGNOSTICO')
                    ->setCellValue('D1', 'IDENTIFICACIÓN')
                    ->setCellValue('E1', 'EMPLEADO')
                    ->setCellValue('F1', 'CENTRO COSTOS')
                    ->setCellValue('G1', 'DESDE')
                    ->setCellValue('H1', 'HASTA')
                    ->setCellValue('I1', 'DÍAS')
                    ->setCellValue('J1', 'PRORROGA')
                    ->setCellValue('K1', 'TRANSCRIPCIÓN')
                    ->setCellValue('L1', 'VR. INCAPACIDAD');

        $i = 2;
        $query = $em->createQuery($this->strSqlIncapacidadesCobrarLista);
        $arIncapacidadesCobrar = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
        $arIncapacidadesCobrar = $query->getResult();
        foreach ($arIncapacidadesCobrar as $arIncapacidadesCobrar) {
            if ($arIncapacidadesCobrar->getEstadoProrroga() == 1){
                $prorroga = "SI";
            }else {
                $prorroga = "NO"; 
            }
            if ($arIncapacidadesCobrar->getEstadoTranscripcion() == 1){
                $transcripcion = "SI";
            }else {
                $transcripcion = "NO"; 
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arIncapacidadesCobrar->getCodigoIncapacidadPk())
                    ->setCellValue('B' . $i, $arIncapacidadesCobrar->getPagoAdicionalSubtipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arIncapacidadesCobrar->getIncapacidadDiagnosticoRel()->getNombre())
                    ->setCellValue('D' . $i, $arIncapacidadesCobrar->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arIncapacidadesCobrar->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arIncapacidadesCobrar->getCentroCostoRel()->getNombre())
                    ->setCellValue('G' . $i, $arIncapacidadesCobrar->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('H' . $i, $arIncapacidadesCobrar->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('I' . $i, $arIncapacidadesCobrar->getCantidad())
                    ->setCellValue('J' . $i, $prorroga)
                    ->setCellValue('K' . $i, $transcripcion)
                    ->setCellValue('L' . $i, $arIncapacidadesCobrar->getVrIncapacidad());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('IncapacidadesCobrar');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteIncapacidadesCobrar.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }
}
