<?php
namespace Brasa\TurnoBundle\Controller\Consulta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
class PedidosDetallesController extends Controller
{
    var $strListaDql = "";
    
    /**
     * @Route("/tur/consulta/pedidos/detalles", name="brs_tur_consulta_pedidos_detalles")
     */    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $this->estadoAnulado = 0;
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {            
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();
                $this->generarExcel();
            }
        }
        
        $arPedidosDetalles = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 200);
        return $this->render('BrasaTurnoBundle:Consultas/Pedido:detalle.html.twig', array(
            'arPedidosDetalles' => $arPedidosDetalles,
            'form' => $form->createView()));
    }
            
    private function lista() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $strFechaDesde = "";
        $strFechaHasta = "";        
        $filtrarFecha = $session->get('filtroPedidoFiltrarFecha');
        if($filtrarFecha) {
            $strFechaDesde = $session->get('filtroPedidoFechaDesde');
            $strFechaHasta = $session->get('filtroPedidoFechaHasta');                    
        }
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->listaConsultaDql(
                $session->get('filtroPedidoNumero'), 
                $session->get('filtroCodigoCliente'), 
                $session->get('filtroPedidoEstadoAutorizado'), 
                $session->get('filtroPedidoEstadoProgramado'),
                $session->get('filtroPedidoEstadoFacturado'),
                $session->get('filtroPedidoEstadoAnulado'),
                $strFechaDesde,
                $strFechaHasta);
    }

    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();        
        $session->set('filtroPedidoNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroPedidoEstadoAutorizado', $form->get('estadoAutorizado')->getData());          
        $session->set('filtroPedidoEstadoProgramado', $form->get('estadoProgramado')->getData());          
        $session->set('filtroPedidoEstadoFacturado', $form->get('estadoFacturado')->getData());          
        $session->set('filtroPedidoEstadoAnulado', $form->get('estadoAnulado')->getData());          
        $session->set('filtroNit', $form->get('TxtNit')->getData());                         
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroPedidoFechaDesde', $dateFechaDesde->format('Y/m/d'));
        $session->set('filtroPedidoFechaHasta', $dateFechaHasta->format('Y/m/d'));                 
        $session->set('filtroPedidoFiltrarFecha', $form->get('filtrarFecha')->getData());
        
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $strNombreCliente = "";
        if($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
            if($arCliente) {
                $session->set('filtroCodigoCliente', $arCliente->getCodigoClientePk());
                $strNombreCliente = $arCliente->getNombreCorto();
            }  else {
                $session->set('filtroCodigoCliente', null);
                $session->set('filtroNit', null);
            }          
        } else {
            $session->set('filtroCodigoCliente', null);
        }       
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/')."01";
        $intUltimoDia = $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFecha->format('m')+1,1,$dateFecha->format('Y'))-1));
        $strFechaHasta = $dateFecha->format('Y/m/').$intUltimoDia;
        if($session->get('filtroPedidoFechaDesde') != "") {
            $strFechaDesde = $session->get('filtroPedidoFechaDesde');
        }
        if($session->get('filtroPedidoFechaHasta') != "") {
            $strFechaHasta = $session->get('filtroPedidoFechaHasta');
        }    
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()
            ->add('TxtNit', 'text', array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', 'text', array('label'  => 'NombreCliente','data' => $strNombreCliente))                
            ->add('TxtNumero', 'text', array('label'  => 'Codigo','data' => $session->get('filtroPedidoNumero')))
            ->add('estadoAutorizado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'AUTORIZADO', '0' => 'SIN AUTORIZAR'), 'data' => $session->get('filtroPedidoEstadoAutorizado')))                
            ->add('estadoProgramado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'PROGRAMADO', '0' => 'SIN PROGRAMAR'), 'data' => $session->get('filtroPedidoEstadoProgramado')))                                
            ->add('estadoFacturado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'FACTURADO', '0' => 'SIN FACTURAR'), 'data' => $session->get('filtroPedidoEstadoFacturado')))                                
            ->add('estadoAnulado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'ANULADO', '0' => 'SIN ANULAR'), 'data' => $session->get('filtroPedidoEstadoAnulado')))                                
            ->add('fechaDesde', 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))                            
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))                
            ->add('filtrarFecha', 'checkbox', array('required'  => false, 'data' => $session->get('filtroPedidoFiltrarFecha')))                             
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
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
        for($col = 'A'; $col !== 'AI'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }      
        for($col = 'AD'; $col !== 'AI'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'TIPO')
                    ->setCellValue('C1', 'NUMERO')
                    ->setCellValue('D1', 'FECHA')
                    ->setCellValue('E1', 'FH PROG')
                    ->setCellValue('F1', 'CLIENTE')
                    ->setCellValue('G1', 'SECTOR')
                    ->setCellValue('H1', 'PROGRAMADO')               
                    ->setCellValue('I1', 'PUESTO')
                    ->setCellValue('J1', 'SERVICIO')
                    ->setCellValue('K1', 'MODALIDAD')
                    ->setCellValue('L1', 'PERIODO')
                    ->setCellValue('M1', 'PLANTILLA')
                    ->setCellValue('N1', 'DESDE')
                    ->setCellValue('O1', 'HASTA')
                    ->setCellValue('P1', 'CANT')
                    ->setCellValue('Q1', 'CANT.R')
                    ->setCellValue('R1', 'LU')
                    ->setCellValue('S1', 'MA')
                    ->setCellValue('T1', 'MI')
                    ->setCellValue('U1', 'JU')
                    ->setCellValue('V1', 'VI')
                    ->setCellValue('W1', 'SA')
                    ->setCellValue('X1', 'DO')
                    ->setCellValue('Y1', 'FE')
                    ->setCellValue('Z1', 'H')
                    ->setCellValue('AA1', 'HD')
                    ->setCellValue('AB1', 'HN')
                    ->setCellValue('AC1', 'HP')
                    ->setCellValue('AD1', 'HDP')
                    ->setCellValue('AE1', 'HNP')                
                    ->setCellValue('AF1', 'DIAS')
                    ->setCellValue('AG1', 'VALOR')
                    ->setCellValue('AH1', 'VR.PEND');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arPedidosDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
        $arPedidosDetalles = $query->getResult();

        foreach ($arPedidosDetalles as $arPedidoDetalle) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPedidoDetalle->getCodigoPedidoDetallePk())
                    ->setCellValue('B' . $i, $arPedidoDetalle->getPedidoRel()->getPedidoTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arPedidoDetalle->getPedidoRel()->getNumero())
                    ->setCellValue('D' . $i, $arPedidoDetalle->getPedidoRel()->getFecha()->format('Y/m/d'))
                    ->setCellValue('E' . $i, $arPedidoDetalle->getPedidoRel()->getFechaProgramacion()->format('Y/m/d'))
                    ->setCellValue('F' . $i, $arPedidoDetalle->getPedidoRel()->getClienteRel()->getNombreCorto())
                    ->setCellValue('G' . $i, $arPedidoDetalle->getPedidoRel()->getSectorRel()->getNombre())
                    ->setCellValue('H' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getPedidoRel()->getEstadoProgramado()))
                    ->setCellValue('J' . $i, $arPedidoDetalle->getConceptoServicioRel()->getNombre())
                    ->setCellValue('K' . $i, $arPedidoDetalle->getModalidadServicioRel()->getNombre())
                    ->setCellValue('L' . $i, $arPedidoDetalle->getPeriodoRel()->getNombre())                    
                    ->setCellValue('N' . $i, $arPedidoDetalle->getDiaDesde())
                    ->setCellValue('O' . $i, $arPedidoDetalle->getDiaHasta())
                    ->setCellValue('P' . $i, $arPedidoDetalle->getCantidad())
                    ->setCellValue('Q' . $i, $arPedidoDetalle->getCantidadRecurso())
                    ->setCellValue('R' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getLunes()))
                    ->setCellValue('S' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getMartes()))
                    ->setCellValue('T' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getMiercoles()))
                    ->setCellValue('U' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getJueves()))
                    ->setCellValue('V' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getViernes()))
                    ->setCellValue('W' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getSabado()))
                    ->setCellValue('X' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getDomingo()))
                    ->setCellValue('Y' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getFestivo()))
                    ->setCellValue('Z' . $i, $arPedidoDetalle->getHoras())
                    ->setCellValue('AA' . $i, $arPedidoDetalle->getHorasDiurnas())
                    ->setCellValue('AB' . $i, $arPedidoDetalle->getHorasNocturnas())
                    ->setCellValue('AC' . $i, $arPedidoDetalle->getHorasProgramadas())
                    ->setCellValue('AD' . $i, $arPedidoDetalle->getHorasDiurnasProgramadas())
                    ->setCellValue('AE' . $i, $arPedidoDetalle->getHorasNocturnasProgramadas())
                    ->setCellValue('AF' . $i, $arPedidoDetalle->getDias())
                    ->setCellValue('AG' . $i, $arPedidoDetalle->getVrTotalDetalle())
                    ->setCellValue('AH' . $i, $arPedidoDetalle->getVrTotalDetallePendiente());
            if($arPedidoDetalle->getPuestoRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('I' . $i, $arPedidoDetalle->getPuestoRel()->getNombre());
            }
            if($arPedidoDetalle->getPlantillaRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('M' . $i, $arPedidoDetalle->getPlantillaRel()->getNombre());
            }            
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('PedidosDetalles');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PedidosDetalles.xlsx"');
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