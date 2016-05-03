<?php
namespace Brasa\AfiliacionBundle\Controller\Base;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\AfiliacionBundle\Form\Type\AfiEmpleadoType;
use Brasa\AfiliacionBundle\Form\Type\AfiContratoType;
class EmpleadoController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/afi/base/empleado", name="brs_afi_base_empleado")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaAfiliacionBundle:AfiEmpleado')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_afi_base_empleado'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }
        
        $arEmpleados = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Base/Empleado:lista.html.twig', array(
            'arEmpleados' => $arEmpleados, 
            'form' => $form->createView()));
    }

    /**
     * @Route("/afi/base/empleado/nuevo/{codigoEmpleado}", name="brs_afi_base_empleado_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoEmpleado = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arEmpleado = new \Brasa\AfiliacionBundle\Entity\AfiEmpleado();
        if($codigoEmpleado != '' && $codigoEmpleado != '0') {
            $arEmpleado = $em->getRepository('BrasaAfiliacionBundle:AfiEmpleado')->find($codigoEmpleado);
        }        
        $form = $this->createForm(new AfiEmpleadoType, $arEmpleado);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arEmpleado = $form->getData(); 
            $arEmpleado->setNombreCorto($arEmpleado->getNombre1() . " " . $arEmpleado->getNombre2() . " " .$arEmpleado->getApellido1() . " " . $arEmpleado->getApellido2());
            $em->persist($arEmpleado);
            $em->flush();            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_afi_base_empleado_nuevo', array('codigoEmpleado' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_afi_base_empleado'));
            }                                   
        }
        return $this->render('BrasaAfiliacionBundle:Base/Empleado:nuevo.html.twig', array(
            'arEmpleado' => $arEmpleado,
            'form' => $form->createView()));
    }        

    /**
     * @Route("/afi/base/empleado/detalle/{codigoEmpleado}", name="brs_afi_base_empleado_detalle")
     */    
    public function detalleAction(Request $request, $codigoEmpleado = '') {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioDetalle();
        $form->handleRequest($request);        
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            
            if($request->request->get('OpGenerar')) {            
                $codigoEmpleado = $request->request->get('OpGenerar');
                $arEmpleado = new \Brasa\AfiliacionBundle\Entity\AfiEmpleado();
                $arEmpleado = $em->getRepository('BrasaAfiliacionBundle:AfiEmpleado')->find($codigoEmpleado);
                $arEmpleado->setEstadoGenerado(1);
                $em->persist($arEmpleado);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_afi_movimiento_empleado'));
            }                            
            if ($form->get('BtnEliminarContrato')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarContrato');
                $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->eliminar($arrSeleccionados);
                //return $this->redirect($this->generateUrl('brs_tur_base_empleado_concepto'));
            }

        }
        $arEmpleado = new \Brasa\AfiliacionBundle\Entity\AfiEmpleado();
        $arEmpleado = $em->getRepository('BrasaAfiliacionBundle:AfiEmpleado')->find($codigoEmpleado);
        $dql = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->listaDetalleDql($codigoEmpleado);        
        $arContratos = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Base/Empleado:detalle.html.twig', array(
            'arEmpleado' => $arEmpleado,
            'arContratos' => $arContratos, 
            'form' => $form->createView()));
    }        
    
    /**
     * @Route("/afi/base/empleado/contrato/nuevo/{codigoEmpleado}/{codigoContrato}", name="brs_afi_base_empleado_contrato_nuevo")
     */    
    public function contratoNuevoAction(Request $request, $codigoEmpleado = '', $codigoContrato = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arContrato = new \Brasa\AfiliacionBundle\Entity\AfiContrato();
        if($codigoContrato != '' && $codigoContrato != '0') {
            $arContrato = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->find($codigoContrato);
        } else {
            $arEmpleado = new \Brasa\AfiliacionBundle\Entity\AfiEmpleado();
            $arEmpleado = $em->getRepository('BrasaAfiliacionBundle:AfiEmpleado')->find($codigoEmpleado);
            $arContrato->setEmpleadoRel($arEmpleado);
        }        
        $form = $this->createForm(new AfiContratoType, $arContrato);
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $em->persist($arContrato);
            $em->flush();            
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                                  
        }
        return $this->render('BrasaAfiliacionBundle:Base/Empleado:contratoNuevo.html.twig', array(
            'form' => $form->createView()));
    }     
    
    private function lista() {    
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiEmpleado')->listaDQL(
                $session->get('filtroEmpleadoNombre')   
                ); 
    }
    
    private function filtrar ($form) {        
        $session = $this->getRequest()->getSession();        
        $session->set('filtroEmpleadoNombre', $form->get('TxtNombre')->getData());
        $this->lista();
    }
    
    private function formularioFiltro() {
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()            
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroEmpleadoNombre')))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))            
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }    

    private function formularioDetalle() {
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()                        
            ->add('BtnEliminarContrato', 'submit', array('label'  => 'Eliminar contrato',))            
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))                        
            ->getForm();
        return $form;
    }         
    
    private function generarExcel() {
        ob_clean();
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'NOMBRE');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
        $arEmpleados = new \Brasa\AfiliacionBundle\Entity\AfiEmpleado();
        $arEmpleados = $query->getResult();
                
        foreach ($arEmpleados as $arEmpleado) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arEmpleado->getCodigoEmpleadoPk())
                    ->setCellValue('B' . $i, $arEmpleado->getNombre());                                    
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Empleado');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Empleados.xlsx"');
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