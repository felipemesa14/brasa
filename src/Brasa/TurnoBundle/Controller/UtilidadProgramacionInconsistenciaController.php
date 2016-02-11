<?php

namespace Brasa\TurnoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use ZipArchive;
use Symfony\Component\HttpFoundation\Response;

class UtilidadProgramacionInconsistenciaController extends Controller
{
    var $strDqlLista = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();  
        $paginator  = $this->get('knp_paginator');        
        $form = $this->formularioLista();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnGenerar')->isClicked()) {          
                $dateFecha = $form->get('fecha')->getData();
                $strAnio = $dateFecha->format('Y');
                $strMes = $dateFecha->format('m');                                
                for($i = 1; $i <= 31; $i++) {
                    $strSql = "SELECT
                                codigo_recurso_fk as codigoRecursoFk, 
                                tur_recurso.nombre_corto as nombreCorto,  
                                dia_$i as dia$i, 
                                tur_turno.nombre as nombreTurno,
                                COUNT(dia_$i) AS numero
                                FROM
                                tur_programacion_detalle
                                LEFT JOIN tur_recurso ON tur_programacion_detalle.codigo_recurso_fk = tur_recurso.codigo_recurso_pk
                                LEFT JOIN tur_turno ON tur_programacion_detalle.dia_$i = tur_turno.codigo_turno_pk
                                WHERE
                                dia_$i IS NOT NULL AND anio = $strAnio AND mes = $strMes
                                GROUP BY
                                codigo_recurso_fk, dia_$i"; 
                    $connection = $em->getConnection();
                    $statement = $connection->prepare($strSql);        
                    $statement->execute();
                    $results = $statement->fetchAll();
                    if(count($results) > 0) {
                        foreach ($results as $registro) {
                            if($registro['numero'] > 1) {
                                $arProgramacionInconsistencia = new \Brasa\TurnoBundle\Entity\TurProgramacionInconsistencia();
                                $arProgramacionInconsistencia->setInconsistencia('Asignacion doble de turno');
                                $arProgramacionInconsistencia->setDetalle("Recurso " . $registro['codigoRecursoFk'] . " " . 
                                        $registro['nombreCorto'] . " dia " . $i . " turno " . $registro['dia'.$i] . " " . $registro['nombreTurno']);
                                $em->persist($arProgramacionInconsistencia);                                
                            }
                        }                        
                    }                        
                }
                $em->flush();
                
                $arRecursos = new \Brasa\TurnoBundle\Entity\TurRecurso();
                $arRecursos =  $em->getRepository('BrasaTurnoBundle:TurRecurso')->findBy(array('estadoActivo' => 1));                
                foreach ($arRecursos as $arRecurso) {
                    $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                    $arProgramacionDetalle =  $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoRecursoFk' => $arRecurso->getCodigoRecursoPk(), 'anio' => $strAnio, 'mes' => $strMes));                
                    if(count($arProgramacionDetalle) <= 0) {
                        $arProgramacionInconsistencia = new \Brasa\TurnoBundle\Entity\TurProgramacionInconsistencia();
                        $arProgramacionInconsistencia->setInconsistencia('Recurso sin programacion en el mes');
                        $arProgramacionInconsistencia->setDetalle("El recurso " . $arRecurso->getCodigoRecursoPk() . " " . $arRecurso->getNombreCorto() . " no registra programaciones para el mes");
                        $em->persist($arProgramacionInconsistencia);                         
                    }
                }
                $em->flush();
                
                return $this->redirect($this->generateUrl('brs_tur_utilidad_programacion_inconsistencias')); 
            } 
            if($form->get('BtnEliminar')->isClicked()) {            
                $strSql = "DELETE FROM tur_programacion_inconsistencia WHERE 1";           
                $em->getConnection()->executeQuery($strSql);
                return $this->redirect($this->generateUrl('brs_tur_utilidad_programacion_inconsistencias')); 
            }
            if($form->get('BtnExportar')->isClicked()) {
                $this->generarExcel();
            }
        }                 
        $dql = $em->getRepository('BrasaTurnoBundle:TurProgramacionInconsistencia')->listaDql();
        $arProgramacionInconsistencias = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 50);
        return $this->render('BrasaTurnoBundle:Utilidades/Programaciones:inconsistencias.html.twig', array(            
            'arProgramacionInconsistencias' => $arProgramacionInconsistencias,
            'form' => $form->createView()));
    }              
    
    private function formularioLista() {                

        $form = $this->createFormBuilder()                        
            ->add('fecha', 'date', array('data' => new \DateTime('now'), 'format' => 'yyyyMMMMdd'))                            
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar'))    
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))    
            ->add('BtnExportar', 'submit', array('label'  => 'Exportar'))    
            ->getForm();        
        return $form;
    }    
    
    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        ob_clean();
        $em = $this->getDoctrine()->getManager();            
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
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'INCONSISTENCIA')
                    ->setCellValue('C1', 'DETALLE');

        $i = 2;
        $dql = $em->getRepository('BrasaTurnoBundle:TurProgramacionInconsistencia')->listaDql();
        $query = $em->createQuery($dql);
        $arProgramacionInconsistencias = new \Brasa\TurnoBundle\Entity\TurProgramacionInconsistencia();
        $arProgramacionInconsistencias = $query->getResult();
        foreach ($arProgramacionInconsistencias as $arProgramacionInconsistencia) {   
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arProgramacionInconsistencia->getCodigoProgramacionInconsistenciaPk())
                    ->setCellValue('B' . $i, $arProgramacionInconsistencia->getInconsistencia())
                    ->setCellValue('C' . $i, $arProgramacionInconsistencia->getDetalle());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Inconsistencias');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="TurnoProgramacionInconsistencias.xlsx"');
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