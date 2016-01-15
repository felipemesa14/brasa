<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuHorarioType;

/**
 * RhuHorario controller.
 *
 */
class BaseHorarioController extends Controller
{

    public function listarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        
        $arHorarios = new \Brasa\RecursoHumanoBundle\Entity\RhuHorario();
        
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoHorarioPk) {
                    $arHorario = new \Brasa\RecursoHumanoBundle\Entity\RhuHorario();
                    $arHorario = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorario')->find($codigoHorarioPk);
                    $em->remove($arHorario);
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('brs_rhu_base_horario_listar'));
            }    
        
        if($form->get('BtnExcel')->isClicked()) {
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
                            ->setCellValue('A1', 'Código')
                            ->setCellValue('B1', 'Nombre')
                            ->setCellValue('C1', 'Entrada')
                            ->setCellValue('D1', 'Salida');

                $i = 2;
                $arHorarios = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorario')->findAll();
                
                foreach ($arHorarios as $arHorarios) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arHorarios->getCodigoHorarioPk())
                            ->setCellValue('B' . $i, $arHorarios->getNombre())
                            ->setCellValue('C' . $i, $arHorarios->getEntrada())
                            ->setCellValue('D' . $i, $arHorarios->getSalida());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Horarios');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Horarios.xlsx"');
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
        $arHorarios = new \Brasa\RecursoHumanoBundle\Entity\RhuHorario();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorario')->findAll();
        $arHorarios = $paginator->paginate($query, $this->get('request')->query->get('page', 1),20);

        return $this->render('BrasaRecursoHumanoBundle:Base/Horario:listar.html.twig', array(
                    'arHorarios' => $arHorarios,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoHorarioPk) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arHorario = new \Brasa\RecursoHumanoBundle\Entity\RhuHorario();
        if ($codigoHorarioPk != 0)
        {
            $arHorario = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorario')->find($codigoHorarioPk);
        }    
        $formHorario = $this->createForm(new RhuHorarioType(), $arHorario);
        $formHorario->handleRequest($request);
        if ($formHorario->isValid())
        {
            // guardar la tarea en la base de datos
            $arHorario = $formHorario->getData();
            $em->persist($arHorario);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_horario_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Horario:nuevo.html.twig', array(
            'formHorario' => $formHorario->createView(),
        ));
    }
    
}