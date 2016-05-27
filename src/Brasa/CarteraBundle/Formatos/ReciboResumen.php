<?php
namespace Brasa\CarteraBundle\Formatos;
class ReciboResumen extends \FPDF_FPDF {
    public static $em;
    
    public static $fechaDesde;
    public static $fechaHasta;
    
    public function Generar($miThis, $fechaDesde, $fechaHasta) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$fechaDesde = $fechaDesde;
        self::$fechaHasta = $fechaHasta;
        $pdf = new ReciboResumen();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("ReciboResumen.pdf", 'D');        
        
    } 
    
    public function Header() {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $this->SetFillColor(200, 200, 200);        
        $this->SetFont('Arial','B',10);
        //Logo
        $this->SetXY(53, 10);
        $this->Image('imagenes/logos/logo.jpg', 12, 7, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->Cell(150, 7, utf8_decode("RESUMEN RECIBOS"), 0, 0, 'C', 1);
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
        
        //$arRecibo = new \Brasa\CarteraBundle\Entity\CarRecibo();
        //$arRecibo = self::$em->getRepository('BrasaCarteraBundle:CarRecibo')->find(self::$codigoRecibo);        
        
        //$arReciboDetalles = new \Brasa\CarteraBundle\Entity\CarReciboDetalle();
        //$arReciboDetalles = self::$em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->findBy(array('codigoReciboFk' => self::$codigoRecibo));
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        
        $intY = 40;
        //linea 1
        $this->SetFillColor(272, 272, 272); 
        $this->SetXY(10, $intY);
        $this->SetFont('Arial','B',8);
        $this->Cell(26, 5, utf8_decode("FECHA DESDE:") , 1, 0, 'L', 1);
        $this->SetFont('Arial',  '',8);
        $this->Cell(52, 5, self::$fechaDesde, 1, 0, 'L', 1);
       
        //linea 2
        $this->SetXY(10, $intY+5);
        $this->SetFont('Arial','B',8);
        $this->Cell(26, 5, utf8_decode("FECHA HASTA:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(52, 5, self::$fechaHasta, 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);            
        
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(14);
        $header = array('TIPO', 'CUENTA', 'NUM', 'TOTAL');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7.5);

        //creamos la cabecera de la tabla.
        $w = array(30, 40, 15, 30);
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
        $strSql = "SELECT
            car_recibo_tipo.nombre AS tipo, 
            gen_cuenta.nombre AS cuenta, 
            COUNT(car_recibo.codigo_recibo_pk) AS numeroRecibos, 
            SUM(car_recibo.vr_total) AS vrTotal
            FROM car_recibo  
            LEFT JOIN car_recibo_tipo ON car_recibo.codigo_recibo_tipo_fk = car_recibo_tipo.codigo_recibo_tipo_pk 
            LEFT JOIN gen_cuenta ON car_recibo.codigo_cuenta_fk = gen_cuenta.codigo_cuenta_pk 
            WHERE car_recibo.fecha >= '" . self::$fechaDesde . "' AND car_recibo.fecha <= '" . self::$fechaHasta . "' 
            GROUP BY car_recibo.codigo_recibo_tipo_fk, car_recibo.codigo_cuenta_fk";
        $connection = self::$em->getConnection();
        $statement = $connection->prepare($strSql);        
        $statement->execute();
        $arRecibosResumen = $statement->fetchAll();   
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7); 
        $total = 0;
        foreach ($arRecibosResumen as $registro) {
            $pdf->Cell(30, 4, $registro['tipo'], 1, 0, 'L');
            $pdf->Cell(40, 4, $registro['cuenta'], 1, 0, 'L');
            $pdf->Cell(15, 4, $registro['numeroRecibos'], 1, 0, 'L');
            $pdf->Cell(30, 4, number_format($registro['vrTotal'], 2, '.', ','), 1, 0, 'R');
            $total += $registro['vrTotal'];
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
        $pdf->Cell(30, 4, '', 0, 0, 'L');
        $pdf->Cell(40, 4, '', 0, 0, 'L');
        $pdf->Cell(15, 4, '', 0, 0, 'L');
        $pdf->Cell(30, 4, number_format($total, 2, '.', ','), 1, 0, 'R'); 
        $pdf->Ln();
        $pdf->Ln();

        $header = array('TIPO', 'NUMERO', 'FECHA', 'CUENTA', 'CLIENTE', 'DCTO', 'AJUSTE', 'RTEICA', 'RTEIVA', 'RTEFTE', 'TOTAL');
        $pdf->SetFillColor(236, 236, 236);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(.2);
        $pdf->SetFont('', 'B', 6);

        //creamos la cabecera de la tabla.
        $w = array(20, 13, 15, 25, 40, 13, 13, 13, 13, 13, 13);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $pdf->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);
            else
                $pdf->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);

        //Restauración de colores y fuentes
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Ln(4);
        $dql   = "SELECT r FROM BrasaCarteraBundle:CarRecibo r JOIN r.reciboTipoRel rt WHERE r.fecha >='" . self::$fechaDesde . "' AND r.fecha <='" . self::$fechaHasta . "'";                
        $query = self::$em->createQuery($dql);
        $arRecibos = $query->getResult();
        foreach ($arRecibos as $arRecibo) {
            $pdf->Cell(20, 4, $arRecibo->getReciboTipoRel()->getNombre(), 1, 0, 'L');                        
            $pdf->Cell(13, 4, $arRecibo->getNumero(), 1, 0, 'L');                        
            $pdf->Cell(15, 4, $arRecibo->getFecha()->format('Y/m/d'), 1, 0, 'L');                        
            $pdf->Cell(25, 4, $arRecibo->getCuentaRel()->getNombre(), 1, 0, 'L');                        
            $pdf->Cell(40, 4, $arRecibo->getClienteRel()->getNombreCorto(), 1, 0, 'L');                        
            $pdf->Cell(13, 4, number_format($arRecibo->getVrTotalDescuento(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(13, 4, number_format($arRecibo->getVrTotalAjustePeso(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(13, 4, number_format($arRecibo->getVrTotalReteIca(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(13, 4, number_format($arRecibo->getVrTotalReteIva(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(13, 4, number_format($arRecibo->getVrTotalReteFuente(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(13, 4, number_format($arRecibo->getVrTotalPago(), 0, '.', ','), 1, 0, 'R');
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