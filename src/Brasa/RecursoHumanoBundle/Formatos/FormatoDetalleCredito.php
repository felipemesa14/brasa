<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoDetalleCredito extends \FPDF_FPDF {
    public static $em;
    public static $codigoCredito;
    public function Generar($miThis, $codigoCredito) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoCredito = $codigoCredito;
        $pdf = new FormatoDetalleCredito();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("DetalleCredito_$codigoCredito.pdf", 'D');        
        
    } 
    public function Header() {
        $arDetallePago = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arDetallePago = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find(self::$codigoCredito);
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',13);
        //$this->Image('imagenes/logos/LogoCotrascal.jpg', 10, 10, 35, 17);        
        $this->SetXY(10, 20);
        $this->Cell(190, 10, "INFORMACION DEL CREDITO " , 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetXY(10, 30);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "CODIGO CREDITO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, $arDetallePago->getCodigoCreditoPk() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "FECHA:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, $arDetallePago->getFecha()->format('Y/m/d') , 1, 0, 'L', 1);
        $this->SetXY(10, 35);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "EMPLEADO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 6, $arDetallePago->getEmpleadoRel()->getNombreCorto() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "CREDITO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 6, $arDetallePago->getCreditoTipoRel()->getNombre() , 1, 0, 'L', 1);
        $this->SetXY(10, 40);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "VALOR CREDITO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, number_format($arDetallePago->getVrPagar(), 2, '.', ',') , 1, 0, 'R', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "VALOR CUOTA:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, number_format($arDetallePago->getVrCuota(), 2, '.', ',') , 1, 0, 'R', 1);
        $this->SetXY(10, 45);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "NUMERO CUOTAS:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, $arDetallePago->getNumeroCuotas() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "CUOTA ACTUAL:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, $arDetallePago->getNumeroCuotaActual() , 1, 0, 'L', 1);
        $this->SetXY(10, 50);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "SALDO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, number_format($arDetallePago->getSaldo(), 2, '.', ','), 1, 0, 'R', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "PAGADO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        if ($arDetallePago->getEstadoPagado()== 1)
        {    
            $this->Cell(65, 6, "SI" , 1, 0, 'L', 1);
        }
        else
        {
            $this->Cell(65, 6, "NO" , 1, 0, 'L', 1);
        }    
        $this->SetXY(10, 55);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "SALDO TEMPORAL:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, number_format($arDetallePago->getSaldoTotal() , 2, '.', ','),1, 0, 'R', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "APROBADO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        if ($arDetallePago->getAprobado()== 1)
        {    
            $this->Cell(65, 6, "SI" , 1, 0, 'L', 1);
        }
        else
        {
            $this->Cell(65, 6, "NO" , 1, 0, 'L', 1);
        }
        $this->SetXY(10, 60);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "TIPO PAGO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, $arDetallePago->getCreditoTipoPagoRel()->getNombre(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "SUSPENDIDO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        if ($arDetallePago->getEstadoSuspendido()== 1)
        {    
            $this->Cell(65, 6, "SI" , 1, 0, 'L', 1);
        }
        else
        {
            $this->Cell(65, 6, "NO" , 1, 0, 'L', 1);
        }
        $this->SetXY(10, 65);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "SEGURO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, number_format($arDetallePago->getSeguro(), 2, '.', ','),1, 0, 'R', 1);
        $this->Cell(95, 6, "" , 1, 0, 'L', 1);
        $this->SetXY(10, 70);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 5, "COMENTARIOS:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(160, 5, $arDetallePago->getComentarios() , 1, 0, 'L', 1);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array('CODIGO PAGO', 'TIPO PAGO', 'PERIODO DESDE', 'PERIODO HASTA','FECHA PAGO', 'VR CUOTA');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 8);

        //creamos la cabecera de la tabla.
        $w = array(25, 45, 30, 30, 30, 30);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
            else
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);

        //Restauraci�n de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf) {
        $arCreditoPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuCreditoPago();
        $arCreditoPagos = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuCreditoPago')->findBy(array('codigoCreditoFk' => self::$codigoCredito));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 8);
        $douTotal = 0;
        foreach ($arCreditoPagos as $arCreditoPago) { 
            $pdf->Cell(25, 4, $arCreditoPago->getCodigoPagoCreditoPk(), 1, 0, 'L');
            $pdf->Cell(45, 4, $arCreditoPago->getCreditoTipoPagoRel()->getNombre(), 1, 0, 'L');
            if ($arCreditoPago->getCodigoPagoFk() != "") {
                $pdf->Cell(30, 4, $arCreditoPago->getPagoRel()->getFechaDesde()->format('Y/m/d'), 1, 0, 'R');
                $pdf->Cell(30, 4, $arCreditoPago->getPagoRel()->getFechaHasta()->format('Y/m/d'), 1, 0, 'R');
            }
            else {
                $pdf->Cell(30, 4, $arCreditoPago->getFechaPago()->format('Y/m/d'), 1, 0, 'R');
                $pdf->Cell(30, 4, $arCreditoPago->getFechaPago()->format('Y/m/d'), 1, 0, 'R');
            }
            $pdf->Cell(30, 4, $arCreditoPago->getFechaPago()->format('Y/m/d'), 1, 0, 'C');
            $pdf->Cell(30, 4, number_format($arCreditoPago->getVrCuota(), 2, '.', ','), 1, 0, 'R');
            $douTotal += $arCreditoPago->getVrCuota();
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 33);
        }
        
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(160, 4, "TOTAL", 1, 0, 'R');
        $pdf->Cell(30, 4, number_format($douTotal, 2, '.', ','), 1, 0, 'R');
    }   

    public function Footer() {
                
        $this->SetFont('Arial','', 8);  
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
