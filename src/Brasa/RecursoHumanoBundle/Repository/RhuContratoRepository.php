<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuContratoRepository extends EntityRepository {
    
    public function listaDQL($strIdentificacion = "", $fechaDesdeInicia = "", $fechaHastaInicia = "", $boolMostrarActivos = 2, $strCodigoCentroCosto = "") {        
        $dql   = "SELECT c, e FROM BrasaRecursoHumanoBundle:RhuContrato c JOIN c.empleadoRel e WHERE c.codigoContratoPk <> 0";
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion LIKE '%" . $strIdentificacion . "%'";
        }
        if($fechaDesdeInicia != "" ) {
            $dql .= " AND c.fechaDesde >= '" . $fechaDesdeInicia . "'";
        }        
        if($fechaHastaInicia != "" ) {
            $dql .= " AND c.fechaDesde <= '" . $fechaHastaInicia . "'";
        }        
        if($boolMostrarActivos == 1 ) {
            $dql .= " AND c.estadoActivo = 1";
        } 
        if($boolMostrarActivos == "0") {
            $dql .= " AND c.estadoActivo = 0";
        }
        if($strCodigoCentroCosto != "") {
            $dql .= " AND c.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }
        $dql .= " ORDER BY c.codigoContratoPk";
        return $dql;
    }  
    
    public function ultimoContrato($codigoCentroCosto = "", $codigoEmpleado = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT c FROM BrasaRecursoHumanoBundle:RhuContrato c "
                ." WHERE c.codigoEmpleadoFk = " . $codigoEmpleado . " AND c.codigoCentroCostoFk = " . $codigoCentroCosto. " "
                . "ORDER BY c.fechaHasta DESC";
        $query = $em->createQuery($dql);        
        $arContrato = $query->getResult();        
        if(count($arContrato) > 0) {
            return $arContrato[0]->getCodigoContratoPk();
        } else {
            return 0;
        }        
    }
    
    public function contratosInicioFecha($fechaDesde = "", $fechaHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT c FROM BrasaRecursoHumanoBundle:RhuContrato c "
                ." WHERE c.fechaDesde >= '" . $fechaDesde . "' AND c.fechaDesde <= '" . $fechaHasta . "' "
                . "ORDER BY c.fechaDesde DESC";
        $query = $em->createQuery($dql);        
        $arContratos = $query->getResult();        
        return $arContratos;
    } 

    public function contratosPeriodo($fechaDesde = "", $fechaHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT c FROM BrasaRecursoHumanoBundle:RhuContrato c "
                ." WHERE (c.fechaHasta >= '" . $fechaDesde . "' OR c.indefinido = 1) "
                . "AND c.fechaDesde <= '" . $fechaHasta . "' ";
        $query = $em->createQuery($dql);        
        $arContratos = $query->getResult();        
        return $arContratos;
    }    
    
    public function contratosPeriodoEmpleado($fechaDesde = "", $fechaHasta = "", $codigoEmpleado) {        
        $em = $this->getEntityManager();
        $dql   = "SELECT c FROM BrasaRecursoHumanoBundle:RhuContrato c "
                ." WHERE (c.fechaHasta >= '" . $fechaDesde . "' OR c.indefinido = 1) "
                . "AND c.fechaDesde <= '" . $fechaHasta . "' "
                . "AND c.codigoEmpleadoFk = " . $codigoEmpleado;
        $query = $em->createQuery($dql);        
        $arContratos = $query->getResult();        
        return $arContratos;
    }        

    public function contratosPeriodoCentroCosto($fechaDesde = "", $fechaHasta = "", $codigoCentroCosto = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT c FROM BrasaRecursoHumanoBundle:RhuContrato c "
                ." WHERE (c.fechaHasta >= '" . $fechaDesde . "' OR c.indefinido = 1) "
                . "AND c.fechaDesde <= '" . $fechaHasta . "' AND c.codigoCentroCostoFk = " . $codigoCentroCosto;
        $query = $em->createQuery($dql);        
        $arContratos = $query->getResult();        
        return $arContratos;
    }     
    
    //verifica si el empleado tiene contratos abiertos
    public function validarEmpleadoContrato($douValidarEmpleadoContrato = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT count(c.codigoContratoPk) FROM BrasaRecursoHumanoBundle:RhuContrato c where c.codigoEmpleadoFk = $douValidarEmpleadoContrato and c.estadoActivo = 1";
        $query = $em->createQuery($dql);
        $duoNumeroContratos = $query->getSingleScalarResult(); 
        return $duoNumeroContratos;
    }
    
    //lista contratos con las vacaciones cumplidas 365 dias
    public function listaContratosVacacionCumplidaDQL($strCodigoCentroCosto = "", $strIdentificacion = "", $strHasta = "") {        
        $dql   = "SELECT c, e FROM BrasaRecursoHumanoBundle:RhuContrato c JOIN c.empleadoRel e WHERE c.codigoContratoPk <> 0 AND c.estadoLiquidado = 0";
        if($strCodigoCentroCosto != "") {
            $dql .= " AND c.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }   
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        if($strHasta != "") {
            $dql .= " AND c.fechaUltimoPagoVacaciones <='" . date_format($strHasta, ('Y-m-d')) . "'";
        }
        return $dql;
    }
    
    //lista contratos con las fecha de vencimiento, no aplica para los contratos a termino indefinido
    public function listaContratosFechaTerminacionDQL($strCodigoContratoTipo = "",$strCodigoEmpleadoTipo = "", $strCodigoZona = "", $strCodigoSubZona = "", $strCodigoCentroCosto = "", $strIdentificacion = "", $strDesde = "", $strHasta = "") {        
        $dql   = "SELECT c, e FROM BrasaRecursoHumanoBundle:RhuContrato c JOIN c.empleadoRel e WHERE c.codigoContratoPk <> 0 AND c.estadoTerminado = 1";
        if($strCodigoContratoTipo != "") {
            $dql .= " AND c.codigoContratoTipoFk = " . $strCodigoContratoTipo;
        }
        if($strCodigoEmpleadoTipo != "") {
            $dql .= " AND e.codigoEmpleadoTipoFk = " . $strCodigoEmpleadoTipo;
        }
        if($strCodigoZona != "") {
            $dql .= " AND e.codigoZonaFk = " . $strCodigoZona;
        }
        if($strCodigoSubZona != "") {
            $dql .= " AND e.codigoSubzonaFk = " . $strCodigoSubZona;
        }
        if($strCodigoCentroCosto != "") {
            $dql .= " AND c.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }   
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        if($strHasta != "") {
            $dql .= " AND c.fechaHasta >='" . date_format($strDesde, ('Y-m-d')) . "'";
        }
        if($strHasta != "") {
            $dql .= " AND c.fechaHasta <='" . date_format($strHasta, ('Y-m-d')) . "'";
        }
        return $dql;
    }
    
    //lista contratos con las fechas de ingresos
    public function listaIngresosContratosDQL($strCodigoContratoTipo = "", $strCodigoEmpleadoTipo = "", $strCodigoZona = "", $strCodigoSubZona = "", $strCodigoContrato = "", $strIdentificacion = "", $strDesde = "", $strHasta = "") {        
        $dql   = "SELECT c, e FROM BrasaRecursoHumanoBundle:RhuContrato c JOIN c.empleadoRel e WHERE c.codigoContratoPk <> 0 ";
        if($strCodigoContratoTipo != "") {
            $dql .= " AND c.codigoContratoTipoFk = " . $strCodigoContratoTipo;
        }
        if($strCodigoEmpleadoTipo != "") {
            $dql .= " AND e.codigoEmpleadoTipoFk = " . $strCodigoEmpleadoTipo;
        }
        if($strCodigoZona != "") {
            $dql .= " AND e.codigoZonaFk = " . $strCodigoZona;
        }
        if($strCodigoSubZona != "") {
            $dql .= " AND e.codigoSubzonaFk = " . $strCodigoSubZona;
        }
        if($strCodigoContrato != "") {
            $dql .= " AND c.codigoContratoPk = " . $strCodigoContrato;
        }   
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        if($strDesde != "") {
            $dql .= " AND c.fechaDesde >='" . date_format($strDesde, ('Y-m-d')) . "'";
        }
        if($strHasta != "") {
            $dql .= " AND c.fechaDesde <='" . date_format($strHasta, ('Y-m-d')) . "'";
        }
        return $dql;
    }
    
    //lista contratos carta laboral
    public function listaContratosCartaLaboralDQL($strCodigoCentroCosto = "", $strIdentificacion = "") {        
        $dql   = "SELECT c, e FROM BrasaRecursoHumanoBundle:RhuContrato c JOIN c.empleadoRel e WHERE c.codigoContratoPk <> 0";
        if($strCodigoCentroCosto != "") {
            $dql .= " AND c.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }   
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        return $dql;
    }
    
    public function numtoletras($xcifra) {
    $em = $this->getEntityManager();
        $xarray = array(0 => "Cero",
        1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE",
        "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE",
        "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA",
        100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS"
    );
//
    $xcifra = trim($xcifra);
    $xlength = strlen($xcifra);
    $xpos_punto = strpos($xcifra, ".");
    $xaux_int = $xcifra;
    $xdecimales = "00";
    if (!($xpos_punto === false)) {
        if ($xpos_punto == 0) {
            $xcifra = "0" . $xcifra;
            $xpos_punto = strpos($xcifra, ".");
        }
        $xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
        $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
    }
 
    $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
    $xcadena = "";
    for ($xz = 0; $xz < 3; $xz++) {
        $xaux = substr($XAUX, $xz * 6, 6);
        $xi = 0;
        $xlimite = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
        $xexit = true; // bandera para controlar el ciclo del While
        while ($xexit) {
            if ($xi == $xlimite) { // si ya llegó al límite máximo de enteros
                break; // termina el ciclo
            }
 
            $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
            $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
            for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
                switch ($xy) {
                    case 1: // checa las centenas
                        if (substr($xaux, 0, 3) < 100) { // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas
                             
                        } else {
                            $key = (int) substr($xaux, 0, 3);
                            if (TRUE === array_key_exists($key, $xarray)){  // busco si la centena es número redondo (100, 200, 300, 400, etc..)
                                $xseek = $xarray[$key];
                                $xsub = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
                                if (substr($xaux, 0, 3) == 100)
                                    $xcadena = " " . $xcadena . " CIEN " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
                            }
                            else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
                                $key = (int) substr($xaux, 0, 1) * 100;
                                $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
                                $xcadena = " " . $xcadena . " " . $xseek;
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 0, 3) < 100)
                        break;
                    case 2: // checa las decenas (con la misma lógica que las centenas)
                        if (substr($xaux, 1, 2) < 10) {
                             
                        } else {
                            $key = (int) substr($xaux, 1, 2);
                            if (TRUE === array_key_exists($key, $xarray)) {
                                $xseek = $xarray[$key];
                                $xsub = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->subfijo($xaux);
                                if (substr($xaux, 1, 2) == 20)
                                    $xcadena = " " . $xcadena . " VEINTE " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3;
                            }
                            else {
                                $key = (int) substr($xaux, 1, 1) * 10;
                                $xseek = $xarray[$key];
                                if (20 == substr($xaux, 1, 1) * 10)
                                    $xcadena = " " . $xcadena . " " . $xseek;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " Y ";
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 1, 2) < 10)
                        break;
                    case 3: // checa las unidades
                        if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada
                             
                        } else {
                            $key = (int) substr($xaux, 2, 1);
                            $xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
                            $xsub = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->subfijo($xaux);
                            $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                        } // ENDIF (substr($xaux, 2, 1) < 1)
                        break;
                } // END SWITCH
            } // END FOR
            $xi = $xi + 3;
        } // ENDDO
 
        if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
            $xcadena.= " DE";
 
        if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
            $xcadena.= " DE";
 
        // ----------- esta línea la puedes cambiar de acuerdo a tus necesidades o a tu país -------
        if (trim($xaux) != "") {
            switch ($xz) {
                case 0:
                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                        $xcadena.= "UN BILLON ";
                    else
                        $xcadena.= " BILLONES ";
                    break;
                case 1:
                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                        $xcadena.= "UN MILLON ";
                    else
                        $xcadena.= " MILLONES ";
                    break;
                case 2:
                    if ($xcifra < 1) {
                        $xcadena = "CERO PESOS M/C";
                    }
                    if ($xcifra >= 1 && $xcifra < 2) {
                        $xcadena = "UN PESO M/C ";
                    }
                    if ($xcifra >= 2) {
                        $xcadena.= " PESOS M/C "; //
                    }
                    break;
            } // endswitch ($xz)
        } // ENDIF (trim($xaux) != "")
        // ------------------      en este caso, para México se usa esta leyenda     ----------------
        $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
        $xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
        $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
    } // ENDFOR ($xz)
    return trim($xcadena);
}

    public function subfijo($xx) { // esta función regresa un subfijo para la cifra
    $xx = trim($xx);
    $xstrlen = strlen($xx);
    if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
        $xsub = "";
    //
    if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
        $xsub = "MIL";
    //
    return $xsub;
}
}