<?php
namespace Brasa\AfiliacionBundle\Controller\Consulta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

//use Brasa\AfiliacionBundle\Form\Type\AfiIngresoType;

class generalController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/afi/consulta/contrato/general", name="brs_afi_consulta_contrato_general")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();        
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {                      
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->formularioFiltro();
                $this->lista();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcel();
                
            }
        }
        
        $arGeneral = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 300);
        return $this->render('BrasaAfiliacionBundle:Consulta/Contrato:general.html.twig', array(
            'arGeneral' => $arGeneral, 
            'form' => $form->createView()));
    }
    
    private function lista() {    
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->listaConsultaGeneralDql(
                $session->get('filtroEmpleadoNombre'),
                $session->get('filtroCodigoCliente'),
                $session->get('filtroEmpleadoIdentificacion'),
                $session->get('filtroDesde'),
                $session->get('filtroHasta')
                ); 
    }       

    private function filtrar ($form) {        
        $session = $this->getRequest()->getSession();                        
        $session->set('filtroNit', $form->get('TxtNit')->getData()); 
        $session->set('filtroEmpleadoNombre', $form->get('TxtNombre')->getData());
        $session->set('filtroEmpleadoIdentificacion', $form->get('TxtNumeroIdentificacion')->getData());
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        if ($form->get('fechaDesde')->getData() == null || $form->get('fechaHasta')->getData() == null){
            $session->set('filtroDesde', $form->get('fechaDesde')->getData());
            $session->set('filtroHasta', $form->get('fechaHasta')->getData());
        } else {
            $session->set('filtroDesde', $dateFechaDesde->format('Y-m-d'));
            $session->set('filtroHasta', $dateFechaHasta->format('Y-m-d'));
        }
        
        $this->lista();
    }
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $strNombreCliente = "";
        if($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
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
        $form = $this->createFormBuilder()            
            ->add('TxtNit', 'text', array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', 'text', array('label'  => 'NombreCliente','data' => $strNombreCliente))                                
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroEmpleadoNombre')))
            ->add('TxtNumeroIdentificacion', 'text', array('label'  => 'Nombre','data' => $session->get('filtroEmpleadoIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;        
    }            

    private function generarExcel() {
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
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
        for($col = 'A'; $col !== 'R'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }      
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'IDENTIFICACION')
                    ->setCellValue('C1', 'TIPO ID')
                    ->setCellValue('D1', 'EMPLEADO')
                    ->setCellValue('E1', 'CIUDAD')
                    ->setCellValue('F1', 'DIRECCION')
                    ->setCellValue('G1', 'BARRIO')
                    ->setCellValue('H1', 'TELEFONO')
                    ->setCellValue('I1', 'CELULAR')
                    ->setCellValue('J1', 'EMAIL')
                    ->setCellValue('K1', 'RH')
                    ->setCellValue('L1', 'ESTADO CIVIL')
                    ->setCellValue('M1', 'FECHA NAC')
                    ->setCellValue('N1', 'SEXO')
                    ->setCellValue('O1', 'CARGO')
                    ->setCellValue('P1', 'FECHA DESDE')
                    ->setCellValue('Q1', 'FECHA HASTA')
                    ->setCellValue('R1', 'TIPO CONTIZANTE')
                    ->setCellValue('S1', 'SUCURSAL')
                    ->setCellValue('T1', 'PENSION')
                    ->setCellValue('U1', 'SALUD')
                    ->setCellValue('V1', 'ARL')
                    ->setCellValue('W1', 'CAJA')
                    ->setCellValue('X1', 'CLIENTE')
                    ->setCellValue('Y1', 'RETIRADO');
        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
        //$arIngresos = new \Brasa\AfiliacionBundle\Entity\AfiEmpleado();
        $arGeneral = $query->getResult();
                
        foreach ($arGeneral as $arGeneral) {
        $ciudad = '';
        if ($arGeneral->getEmpleadoRel()->getCodigoCiudadFk() != null){
            $ciudad = $arGeneral->getEmpleadoRel()->getCiudadRel()->getNombre();
        }
        $rh = '';
        if ($arGeneral->getEmpleadoRel()->getCodigoRhPk() != null){
            $rh = $arGeneral->getEmpleadoRel()->getRhRel()->getTipo();
        }
        $estadoCivil = '';
        if ($arGeneral->getEmpleadoRel()->getCodigoEstadoCivilFk() != null){
            $estadoCivil = $arGeneral->getEmpleadoRel()->getEstadoCivilRel()->getNombre();
        }
        if ($arGeneral->getEmpleadoRel()->getCodigoSexoFk() == 'M'){
            $sexo = 'MASCULINO';
        } else {
            $sexo = 'FEMENINO';
        }
        if ($arGeneral->getEmpleadoRel()->getCodigoContratoActivo() == null){
            $codigoContratoActivo = 0;
        } else {
            $codigoContratoActivo = $arGeneral->getEmpleadoRel()->getCodigoContratoActivo();
        }
        $arContrato = new \Brasa\AfiliacionBundle\Entity\AfiContrato();
        $arContrato = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->find($codigoContratoActivo);
        
        $cargo = '';
        $fechaDesde = '';
        $fechaHasta = '';
        $tipoCotizante = '';
        $sucursal = '';
        $pension = '';
        $salud = '';
        $arl = '';
        $caja = '';
        if ($arContrato != null){
            
            if ($arContrato->getCodigoCargoFk() != null){
                $cargo = $arContrato->getCargoRel()->getNombre();
            }
            if ($arContrato->getFechaDesde() != null){
                $fechaDesde = $arContrato->getFechaDesde()->format('Y-m-d');
            }
            if ($arContrato->getFechaHasta() != null){
                $fechaHasta = $arContrato->getFechaHasta()->format('Y-m-d');
            }
            if ($arContrato->getCodigoTipoCotizanteFk() != null){
                $tipoCotizante = $arContrato->getSsoTipoCotizanteRel()->getNombre();
            }
            if ($arContrato->getCodigoSucursalFk() != null){
                $sucursal = $arContrato->getSucursalRel()->getNombre();
            }
            if ($arContrato->getCodigoEntidadPensionFk() != null){
                $pension = $arContrato->getEntidadPensionRel()->getNombre();
            }if ($arContrato->getCodigoEntidadSaludFk() != null){
                $salud = $arContrato->getEntidadSaludRel()->getNombre();
            }
            if ($arContrato->getCodigoClasificacionRiesgoFk() != null){
                $arl = $arContrato->getClasificacionRiesgoRel()->getNombre();
            }
            if ($arContrato->getCodigoEntidadCajaFk() != null){
                $caja = $arContrato->getEntidadCajaRel()->getNombre();
            }
            if ($arContrato->getIndefinido() == 1){
                $independiente = 'NO';
            } else {
                $independiente = 'SI';
            }
        }
        $cliente = '';
        if ($arGeneral->getEmpleadoRel()->getCodigoClienteFk() != null){
            $cliente = $arGeneral->getEmpleadoRel()->getClienteRel()->getNombreCorto();
        }
        
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arGeneral->getCodigoEmpleadoFk())
                    ->setCellValue('B' . $i, $arGeneral->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('C' . $i, $arGeneral->getEmpleadoRel()->getTipoIdentificacionRel()->getNombre())
                    ->setCellValue('D' . $i, $arGeneral->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $ciudad)
                    ->setCellValue('F' . $i, $arGeneral->getEmpleadoRel()->getDireccion())
                    ->setCellValue('G' . $i, $arGeneral->getEmpleadoRel()->getBarrio())
                    ->setCellValue('H' . $i, $arGeneral->getEmpleadoRel()->getTelefono())
                    ->setCellValue('I' . $i, $arGeneral->getEmpleadoRel()->getCelular())
                    ->setCellValue('J' . $i, $arGeneral->getEmpleadoRel()->getCorreo())
                    ->setCellValue('K' . $i, $rh)
                    ->setCellValue('L' . $i, $estadoCivil)
                    ->setCellValue('M' . $i, $arGeneral->getEmpleadoRel()->getFechaNacimiento()->format('Y-m-d'))
                    ->setCellValue('N' . $i, $sexo)
                    ->setCellValue('O' . $i, $cargo)
                    ->setCellValue('P' . $i, $fechaDesde)
                    ->setCellValue('Q' . $i, $fechaHasta)
                    ->setCellValue('R' . $i, $tipoCotizante)
                    ->setCellValue('S' . $i, $sucursal)
                    ->setCellValue('T' . $i, $pension)
                    ->setCellValue('U' . $i, $salud)
                    ->setCellValue('V' . $i, $arl)
                    ->setCellValue('W' . $i, $caja)
                    ->setCellValue('X' . $i, $cliente)
                    ->setCellValue('Y' . $i, $independiente);                                    
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('EmpleadoContrato');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="EmpleadosContratos.xlsx"');
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