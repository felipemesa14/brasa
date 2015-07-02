<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoSalud extends \FPDF_FPDF {
    public static $em;
   
    public function Generar($miThis) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        $pdf = new FormatoSalud();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 11);
        $this->Body($pdf);
        $pdf->Output("Lista_entidades_salud.pdf", 'D');        
        
    } 
    
    public function Header() {
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',12);
        //$this->Image('imagenes/logos/LogoCotrascal.jpg', 10, 10, 35, 17);        
        $this->SetXY(10, 20);
        $this->Cell(190, 8, "LISTADO ENTIDADES DE SALUD " , 1, 0, 'C', 1);
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(8);
        $header = array('ID', 'NOMBRE', 'NIT', 'DIRECCION', 'TELEFONO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('Arial', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(8, 95, 16, 55, 16);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);
            else
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);

        //Restauracion de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf) {
        $arEntidadesSalud = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud();
        $arEntidadesSalud = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadSalud')->findAll();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arEntidadesSalud as $arEntidadesSalud) {            
            $pdf->Cell(8, 4, $arEntidadesSalud->getCodigoEntidadSaludPk(), 1, 0, 'L');
            $pdf->Cell(95, 4, $arEntidadesSalud->getNombre(), 1, 0, 'L');
            $pdf->Cell(16, 4, $arEntidadesSalud->getNit(), 1, 0, 'L');
            $pdf->Cell(55, 4, $arEntidadesSalud->getDireccion(), 1, 0, 'L');
            $pdf->Cell(16, 4, $arEntidadesSalud->getTelefono(), 1, 0, 'L');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);//33
        }        
    }

    public function Footer() {
        $this->SetXY(160, 270);
        $this->Cell(30, 35, utf8_decode('JG Efectivos S.A.S.   Página ') . $this->PageNo() . ' de {nb}' , 0, 0, 'L', 0);          
    }    
}
