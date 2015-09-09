<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoSeleccionGrupo extends \FPDF_FPDF {
    public static $em;
    public static $codigoSeleccionGrupo;
    public function Generar($miThis, $codigoSeleccionGrupo) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoSeleccionGrupo = $codigoSeleccionGrupo;
        $pdf = new FormatoSeleccionGrupo();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("SeleccionGrupo$codigoSeleccionGrupo.pdf", 'D');        
        
    } 
    public function Header() {
        $arSeleccionGrupo = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionGrupo();
        $arSeleccionGrupo = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionGrupo')->find(self::$codigoSeleccionGrupo);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $this->SetFillColor(200, 200, 200);        
        $this->SetFont('Arial','B',10);
        //Logo
        $this->SetXY(53, 10);
        $this->Image('imagenes/logos/logo.jpg', 12, 7, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->Cell(150, 7, utf8_decode("GRUPO SELECCIÓN ". $arSeleccionGrupo->getCodigoSeleccionGrupoPk()." ". $arSeleccionGrupo->getNombre()), 0, 0, 'C', 1);
        $this->SetXY(53, 18);
        $this->SetFont('Arial','B',9);
        $this->Cell(20, 4, "EMPRESA:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNombreEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 22);
        $this->Cell(20, 4, "NIT:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNitEmpresa()." - ". $arConfiguracion->getDigitoVerificacionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 26);
        $this->Cell(20, 4, utf8_decode("DIRECCIÓN:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getDireccionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 30);
        $this->Cell(20, 4, utf8_decode("TELÉFONO:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getTelefonoEmpresa(), 0, 0, 'L', 0);
        
        
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(8);
        $header = array('ID', 'TIPO', 'FECHA PRUEBA','IDENTIFICACION', 'NOMBRE', 'TELEFONO', 'CELULAR');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(10, 40, 22, 22, 65, 15, 20);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);
            else
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);

        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf) {
        $arSelecciones = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
        $arSelecciones = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->findBy(array('codigoSeleccionGrupoFk' => self::$codigoSeleccionGrupo));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arSelecciones as $arSeleccion) {            
            $pdf->Cell(10, 4, $arSeleccion->getCodigoSeleccionPk(), 1, 0, 'L');
            $pdf->Cell(40, 4, $arSeleccion->getSeleccionTipoRel()->getNombre(), 1, 0, 'L');
            $pdf->Cell(22, 4, $arSeleccion->getFechaPruebas()->format('Y/m/d'), 1, 0, 'L');
            $pdf->Cell(22, 4, $arSeleccion->getNumeroIdentificacion(), 1, 0, 'L');
            $pdf->Cell(65, 4, utf8_decode($arSeleccion->getNombreCorto()), 1, 0, 'L');
            $pdf->Cell(15, 4, $arSeleccion->getTelefono(), 1, 0, 'L');
            $pdf->Cell(20, 4, $arSeleccion->getCelular(), 1, 0, 'L');            
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }        
    }

    public function Footer() {
        $this->SetFont('Arial','', 8);  
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
