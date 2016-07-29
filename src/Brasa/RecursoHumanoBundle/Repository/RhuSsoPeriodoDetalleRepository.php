<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuSsoPeriodoDetalleRepository extends EntityRepository {
    
    public function listaDQL($codigoPeriodo) {                    
            $dql   = "SELECT pd FROM BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle pd WHERE pd.codigoPeriodoFk = " . $codigoPeriodo;
            return $dql;
        } 
    
    public function generar($codigoPeriodoDetalle) {
        $em = $this->getEntityManager();
        set_time_limit(0);
        $intNumeroEmpleados = 0;
        $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        $arPeriodoEmpleadoValidar = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();
        $arPeriodoEmpleadoValidar = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->findOneBy(array('codigoPeriodoDetalleFk' => $codigoPeriodoDetalle));        
        if ($arPeriodoEmpleadoValidar == null){
            return false;
        } else {
            $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
            $arPeriodoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);        
            $arPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo();
            $arPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->find($arPeriodoDetalle->getCodigoPeriodoFk());
            if ($arPeriodoDetalle->getEstadoActualizado() == 0){
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->actualizar($codigoPeriodoDetalle);
                $arPeriodoDetalle->setEstadoActualizado(1);
                $em->persist($arPeriodoDetalle);
            }
            $totalCotizacionGeneral = 0;
            $i = 1;
            $arPeriodoEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();
            $arPeriodoEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->findBy(array('codigoPeriodoFk' => $arPeriodoDetalle->getCodigoPeriodoFk(), 'codigoPeriodoDetalleFk' => $codigoPeriodoDetalle));                
            $intNumeroEmpleados = count($arPeriodoEmpleados);
            foreach ($arPeriodoEmpleados as $arPeriodoEmpleado) {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arPeriodoEmpleado->getCodigoEmpleadoFk());        
                $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arPeriodoEmpleado->getCodigoContratoFk());        
                $arAporte = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte();
                $arAporte->setSsoPeriodoRel($arPeriodoEmpleado->getSsoPeriodoRel());
                $arAporte->setSsoPeriodoDetalleRel($arPeriodoDetalle);
                $arAporte->setSsoSucursalRel($arPeriodoEmpleado->getSsoSucursalRel());
                $arAporte->setEmpleadoRel($arPeriodoEmpleado->getEmpleadoRel());
                $arAporte->setContratoRel($arPeriodoEmpleado->getContratoRel());
                $arAporte->setAnio($arPeriodo->getAnioPago());
                $arAporte->setMes($arPeriodo->getMesPago());
                $arAporte->setFechaDesde($arPeriodo->getFechaDesde());
                $arAporte->setFechaHasta($arPeriodo->getFechaHasta());
                $arAporte->setTipoRegistro(2);
                $arAporte->setSecuencia($i);
                $arAporte->setTipoDocumento($arEmpleado->getTipoIdentificacionRel()->getCodigoInterface());
                $arAporte->setTipoCotizante($arPeriodoEmpleado->getContratoRel()->getCodigoTipoCotizanteFk());
                $arAporte->setSubtipoCotizante($arPeriodoEmpleado->getContratoRel()->getCodigoSubtipoCotizanteFk());
                $arAporte->setExtranjeroNoObligadoCotizarPension(' ');
                $arAporte->setColombianoResidenteExterior(' ');
                //$arAporte->setCodigoDepartamentoUbicacionlaboral($arPeriodoEmpleado->getContratoRel()->getCentroCostoRel()->getCiudadRel()->getDepartamentoRel()->getCodigoDane());
                //$arAporte->setCodigoMunicipioUbicacionlaboral($arPeriodoEmpleado->getContratoRel()->getCentroCostoRel()->getCiudadRel()->getCodigoDane());
                $arAporte->setCodigoDepartamentoUbicacionlaboral($arPeriodoEmpleado->getEmpleadoRel()->getCiudadRel()->getDepartamentoRel()->getCodigoDane());
                $arAporte->setCodigoMunicipioUbicacionlaboral($arPeriodoEmpleado->getEmpleadoRel()->getCiudadRel()->getCodigoDane());
                $arAporte->setPrimerNombre($arEmpleado->getNombre1());
                $arAporte->setSegundoNombre($arEmpleado->getNombre2());
                $arAporte->setPrimerApellido($arEmpleado->getApellido1());
                $arAporte->setSegundoApellido($arEmpleado->getApellido2());
                $arAporte->setIngreso($arPeriodoEmpleado->getIngreso());
                $arAporte->setRetiro($arPeriodoEmpleado->getRetiro());            
                $arAporte->setCargoRel($arContrato->getCargoRel());
                //Parametros generales
                $floSalario = $arPeriodoEmpleado->getVrSalario();
                $floSalarioIntegral = $arPeriodoEmpleado->getVrSalario();
                if($arPeriodoEmpleado->getSalarioIntegral() == 'X') {
                    $arAporte->setSalarioIntegral($arPeriodoEmpleado->getSalarioIntegral());                
                    $floSalario = $floSalario*70/100;
                } else {
                    $arAporte->setSalarioIntegral(' ');                
                }
                $floSuplementario = $arPeriodoEmpleado->getVrSuplementario();            
                $floIbcIncapacidades = 0;

                if($arPeriodoEmpleado->getVrSuplementario() > 0) {
                    $arAporte->setVariacionTransitoriaSalario('X');
                    $arAporte->setSuplementario($arPeriodoEmpleado->getVrSuplementario());
                }
                if($arPeriodoEmpleado->getDiasIncapacidadGeneral() > 0) {
                    $arAporte->setIncapacidadGeneral('X');
                    $arAporte->setDiasIncapacidadGeneral($arPeriodoEmpleado->getDiasIncapacidadGeneral());
                    $floSalarioMesActual = $floSalario /*+ $floSuplementario*/;   
                    $floSalarioMesAnterior = $this->ibcMesAnterior($arEmpleado->getCodigoEmpleadoPk(), $arPeriodoDetalle->getSsoPeriodoRel()->getMes(), $arPeriodoDetalle->getSsoPeriodoRel()->getAnio());
                    $floIbcIncapacidadGeneral = $this->liquidarIncapacidadGeneral($floSalarioMesActual, $floSalarioMesAnterior, $arPeriodoEmpleado->getDiasIncapacidadGeneral());                        
                    $floIbcIncapacidades += $floIbcIncapacidadGeneral;                
                }
                if($arPeriodoEmpleado->getDiasLicenciaMaternidad() > 0) {
                    $arAporte->setLicenciaMaternidad('X');
                    $arAporte->setDiasLicenciaMaternidad($arPeriodoEmpleado->getDiasLicenciaMaternidad());
                }       
                if($arPeriodoEmpleado->getDiasIncapacidadLaboral() > 0) {
                    $arAporte->setIncapacidadAccidenteTrabajoEnfermedadProfesional($arPeriodoEmpleado->getDiasIncapacidadLaboral());
                    $floSalarioMesActual = $floSalario /*+ $floSuplementario*/;   
                    $floSalarioMesAnterior = $this->ibcMesAnterior($arEmpleado->getCodigoEmpleadoPk(), $arPeriodoDetalle->getSsoPeriodoRel()->getMes(), $arPeriodoDetalle->getSsoPeriodoRel()->getAnio());
                    $floIbcIncapacidadLaboral = $this->liquidarIncapacidadLaboral($floSalarioMesActual, $floSalarioMesAnterior, $arPeriodoEmpleado->getDiasIncapacidadLaboral());                        
                    $floIbcIncapacidades += $floIbcIncapacidadLaboral;                                        
                }          
                if($arPeriodoEmpleado->getDiasVacaciones() > 0) {
                    $arAporte->setVacaciones('X');
                    $arAporte->setDiasVacaciones($arPeriodoEmpleado->getDiasVacaciones());
                }   
                
                if($arPeriodoEmpleado->getSalarioIntegral() == 'X') {
                    $arAporte->setSalarioBasico($floSalarioIntegral);            
                } else {
                    $arAporte->setSalarioBasico($floSalario);            
                }                                    
                $arAporte->setCodigoEntidadPensionPertenece($arPeriodoEmpleado->getCodigoEntidadPensionPertenece());
                $arAporte->setCodigoEntidadSaludPertenece($arPeriodoEmpleado->getCodigoEntidadSaludPertenece());
                $arAporte->setCodigoEntidadCajaPertenece($arPeriodoEmpleado->getCodigoEntidadCajaPertenece());

                //Dias
                $intDiasLicenciaNoRemunerada = $arPeriodoEmpleado->getDiasLicencia();
                $intDiasIncapacidades = $arPeriodoEmpleado->getDiasIncapacidadGeneral() + $arPeriodoEmpleado->getDiasIncapacidadLaboral();
                $intDiasLicenciaMaternidad = $arPeriodoEmpleado->getDiasLicenciaMaternidad();
                $intDiasVacaciones = $arPeriodoEmpleado->getDiasVacaciones();

                $intDiasCotizar = $arPeriodoEmpleado->getDias();
                $intDiasCotizarPension = $intDiasCotizar - $intDiasLicenciaNoRemunerada;
                $intDiasCotizarSalud = $intDiasCotizar - $intDiasLicenciaNoRemunerada;
                // vacaciones afecta parafiscales PABLO 28-07-2016
                if($arConfiguracionNomina->getAfectaVacacionesParafiscales() == true){
                    $intDiasCotizarRiesgos = $intDiasCotizar - $intDiasIncapacidades - $intDiasLicenciaNoRemunerada - $intDiasLicenciaMaternidad - $intDiasVacaciones;
                    $intDiasCotizarCaja = $intDiasCotizar - $intDiasIncapacidades - $intDiasLicenciaNoRemunerada - $intDiasLicenciaMaternidad - $intDiasVacaciones;
                } else {
                    $intDiasCotizarRiesgos = $intDiasCotizar - $intDiasIncapacidades - $intDiasLicenciaNoRemunerada - $intDiasLicenciaMaternidad - $intDiasVacaciones;
                    $intDiasCotizarCaja = $intDiasCotizar - $intDiasIncapacidades - $intDiasLicenciaNoRemunerada - $intDiasLicenciaMaternidad;
                }
                
                // fin
                if($arAporte->getTipoCotizante() == '19' || $arAporte->getTipoCotizante() == '12' || $arAporte->getTipoCotizante() == '23') {
                    $intDiasCotizarPension = 0;
                    $intDiasCotizarCaja = 0;
                }            
                if($arAporte->getTipoCotizante() == '12' || $arAporte->getTipoCotizante() == '19') {
                    $intDiasCotizarRiesgos = 0;
                }             
                $arAporte->setDiasCotizadosPension($intDiasCotizarPension);
                $arAporte->setDiasCotizadosSalud($intDiasCotizarSalud);
                $arAporte->setDiasCotizadosRiesgosProfesionales($intDiasCotizarRiesgos);
                $arAporte->setDiasCotizadosCajaCompensacion($intDiasCotizarCaja);                                  

                //Ibc
                $floIbcBrutoPension = (($intDiasCotizarPension - $intDiasIncapacidades) * ($floSalario / 30)) + $floIbcIncapacidades + $floSuplementario;
                $floIbcBrutoSalud = (($intDiasCotizarSalud - $intDiasIncapacidades) * ($floSalario / 30)) + $floIbcIncapacidades + $floSuplementario;                    
                $floIbcBrutoRiesgos = ($intDiasCotizarRiesgos * ($floSalario / 30)) + $floSuplementario;
                $floIbcBrutoCaja = ($intDiasCotizarCaja * ($floSalario / 30)) + $floSuplementario;

                $floIbcPension = $this->redondearIbc($intDiasCotizarPension, $floIbcBrutoPension);
                $floIbcSalud = $this->redondearIbc($intDiasCotizarSalud, $floIbcBrutoSalud);
                $floIbcRiesgos = $this->redondearIbc($intDiasCotizarRiesgos, $floIbcBrutoRiesgos);
                $floIbcCaja = $this->redondearIbc($intDiasCotizarCaja, $floIbcBrutoCaja);                            

                if($intDiasCotizarRiesgos <= 0) {
                    $floIbcRiesgos = 0;
                }
                $arAporte->setIbcPension($floIbcPension);
                $arAporte->setIbcSalud($floIbcSalud);
                $arAporte->setIbcRiesgosProfesionales($floIbcRiesgos);
                $arAporte->setIbcCaja($floIbcCaja);                                    

                $floTarifaPension = $arPeriodoEmpleado->getTarifaPension() + 4;            
                $floTarifaSalud = 4;
                $floTarifaRiesgos = $arPeriodoEmpleado->getTarifaRiesgos();
                $floTarifaCaja = 4;
                $floTarifaIcbf = 0;
                $floTarifaSena = 0;
                if($arPeriodoEmpleado->getContratoRel()->getCodigoTipoCotizanteFk() == 19 || $arPeriodoEmpleado->getContratoRel()->getCodigoTipoCotizanteFk() == 12) {
                    $floTarifaSalud = 12.5;
                }
                $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                if((($floSalario + $floSuplementario) > (10 * $arConfiguracionNomina->getVrSalario()))) {
                    $floTarifaSalud = 12.5;  
                    $floTarifaIcbf = 3;
                    $floTarifaSena = 2;                
                }

                $arAporte->setTarifaPension($floTarifaPension);
                $arAporte->setTarifaSalud($floTarifaSalud);
                $arAporte->setTarifaRiesgos($floTarifaRiesgos);
                $arAporte->setTarifaCaja($floTarifaCaja);
                $arAporte->setTarifaIcbf($floTarifaIcbf);
                $arAporte->setTarifaSena($floTarifaSena);

                $floCotizacionFSPSolidaridad = 0;
                $floCotizacionFSPSubsistencia = 0;            
                $floAporteVoluntarioFondoPensionesObligatorias = 0;
                $floCotizacionVoluntariaFondoPensionesObligatorias = 0;

                $floCotizacionPension = $this->redondearAporte($floSalario + $floSuplementario, $floIbcPension, $floTarifaPension, $intDiasCotizarPension);            
                
                if($floIbcPension >= ($arConfiguracionNomina->getVrSalario() * 4)) {
                    $floCotizacionFSPSolidaridad = round($floIbcPension * 0.005, -2, PHP_ROUND_HALF_DOWN);
                    $floCotizacionFSPSubsistencia = round($floIbcPension * 0.005, -2, PHP_ROUND_HALF_DOWN);
                }
                
                $floCotizacionSalud = $this->redondearAporte($floSalario + $floSuplementario, $floIbcSalud, $floTarifaSalud, $intDiasCotizarSalud);
                $floCotizacionRiesgos = $this->redondearAporte($floSalario + $floSuplementario, $floIbcRiesgos, $floTarifaRiesgos, $intDiasCotizarRiesgos);
                $floCotizacionCaja = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaCaja, $intDiasCotizarCaja);
                $floCotizacionIcbf = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaIcbf, $intDiasCotizarCaja);
                $floCotizacionSena = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaSena, $intDiasCotizarCaja);
                $floTotalCotizacionFondos = $floAporteVoluntarioFondoPensionesObligatorias + $floCotizacionVoluntariaFondoPensionesObligatorias + $floCotizacionPension;
                
                $arAporte->setAporteVoluntarioFondoPensionesObligatorias($floAporteVoluntarioFondoPensionesObligatorias);
                $arAporte->setCotizacionVoluntarioFondoPensionesObligatorias($floCotizacionVoluntariaFondoPensionesObligatorias);
                $arAporte->setAportesFondoSolidaridadPensionalSolidaridad($floCotizacionFSPSolidaridad);
                $arAporte->setAportesFondoSolidaridadPensionalSubsistencia($floCotizacionFSPSolidaridad);
                $arAporte->setTotalCotizacionFondos($floTotalCotizacionFondos);
                $arAporte->setCotizacionPension($floCotizacionPension);
                $arAporte->setCotizacionSalud($floCotizacionSalud);
                $arAporte->setCotizacionRiesgos($floCotizacionRiesgos);
                $arAporte->setCotizacionCaja($floCotizacionCaja); 
                $arAporte->setCotizacionIcbf($floCotizacionIcbf);
                $arAporte->setCotizacionSena($floCotizacionSena);
                $arAporte->setCentroTrabajoCodigoCt($arPeriodoEmpleado->getContratoRel()->getCodigoCentroCostoFk());
                $totalCotizacion = $floTotalCotizacionFondos + $floCotizacionSalud + $floCotizacionRiesgos + $floCotizacionCaja + $floCotizacionIcbf+$floCotizacionSena+$floCotizacionFSPSolidaridad+$floCotizacionFSPSubsistencia;
                $totalCotizacionGeneral += $totalCotizacion;
                $arAporte->setTotalCotizacion($totalCotizacion);
                $em->persist($arAporte);                
                $i++;
                //Para las licencias segunda linea solo licencias
                if($intDiasLicenciaNoRemunerada > 0) {
                    $arAporte = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte();
                    $arAporte->setSsoPeriodoRel($arPeriodoEmpleado->getSsoPeriodoRel());
                    $arAporte->setSsoPeriodoDetalleRel($arPeriodoDetalle);
                    $arAporte->setSsoSucursalRel($arPeriodoEmpleado->getSsoSucursalRel());
                    $arAporte->setEmpleadoRel($arPeriodoEmpleado->getEmpleadoRel());
                    $arAporte->setContratoRel($arPeriodoEmpleado->getContratoRel());
                    $arAporte->setTipoRegistro(2);
                    $arAporte->setSecuencia($i);
                    $arAporte->setTipoDocumento($arEmpleado->getTipoIdentificacionRel()->getCodigoInterface());
                    $arAporte->setTipoCotizante($arPeriodoEmpleado->getContratoRel()->getCodigoTipoCotizanteFk());
                    $arAporte->setSubtipoCotizante($arPeriodoEmpleado->getContratoRel()->getCodigoSubtipoCotizanteFk());
                    $arAporte->setExtranjeroNoObligadoCotizarPension(' ');
                    $arAporte->setColombianoResidenteExterior(' ');
                    $arAporte->setCodigoDepartamentoUbicacionlaboral($arPeriodoEmpleado->getContratoRel()->getCentroCostoRel()->getCiudadRel()->getDepartamentoRel()->getCodigoDane());
                    $arAporte->setCodigoMunicipioUbicacionlaboral($arPeriodoEmpleado->getContratoRel()->getCentroCostoRel()->getCiudadRel()->getCodigoDane());
                    $arAporte->setPrimerNombre($arEmpleado->getNombre1());
                    $arAporte->setSegundoNombre($arEmpleado->getNombre2());
                    $arAporte->setPrimerApellido($arEmpleado->getApellido1());
                    $arAporte->setSegundoApellido($arEmpleado->getApellido2());

                    //Parametros generales               
                    $floSuplementario = $arPeriodoEmpleado->getVrSuplementario();            
                    $floIbcIncapacidades = 0;

                    if($arPeriodoEmpleado->getDiasLicencia() > 0) {
                        $arAporte->setSuspensionTemporalContratoLicenciaServicios('X');
                        $arAporte->setDiasLicencia($arPeriodoEmpleado->getDiasLicencia());
                    }
                    if($arPeriodoEmpleado->getDiasIncapacidadGeneral() > 0) {                    
                        $arAporte->setDiasIncapacidadGeneral($arPeriodoEmpleado->getDiasIncapacidadGeneral());
                        $floSalarioMesActual = $floSalario + $floSuplementario;   
                        $floSalarioMesAnterior = $this->ibcMesAnterior($arEmpleado->getCodigoEmpleadoPk(), $arPeriodoDetalle->getSsoPeriodoRel()->getMes(), $arPeriodoDetalle->getSsoPeriodoRel()->getAnio());
                        $floIbcIncapacidadGeneral = $this->liquidarIncapacidadGeneral($floSalarioMesActual, $floSalarioMesAnterior, $arPeriodoEmpleado->getDiasIncapacidadGeneral());                        
                        $floIbcIncapacidades += $floIbcIncapacidadGeneral;                
                    }       
                    if($arPeriodoEmpleado->getDiasIncapacidadLaboral() > 0) {                    
                        $floSalarioMesActual = $floSalario + $floSuplementario;   
                        $floSalarioMesAnterior = $this->ibcMesAnterior($arEmpleado->getCodigoEmpleadoPk(), $arPeriodoDetalle->getSsoPeriodoRel()->getMes(), $arPeriodoDetalle->getSsoPeriodoRel()->getAnio());
                        $floIbcIncapacidadLaboral = $this->liquidarIncapacidadLaboral($floSalarioMesActual, $floSalarioMesAnterior, $arPeriodoEmpleado->getDiasIncapacidadLaboral());                        
                        $floIbcIncapacidades += $floIbcIncapacidadLaboral;                                        
                    }                        
                    
                    if($arPeriodoEmpleado->getSalarioIntegral() == 'X') {
                        $arAporte->setSalarioBasico($floSalarioIntegral);            
                    } else {
                        $arAporte->setSalarioBasico($floSalario);            
                    }            
                    $arAporte->setCodigoEntidadPensionPertenece($arPeriodoEmpleado->getCodigoEntidadPensionPertenece());
                    $arAporte->setCodigoEntidadSaludPertenece($arPeriodoEmpleado->getCodigoEntidadSaludPertenece());
                    $arAporte->setCodigoEntidadCajaPertenece($arPeriodoEmpleado->getCodigoEntidadCajaPertenece());

                    //Dias
                    $intDiasLicenciaNoRemunerada = $arPeriodoEmpleado->getDiasLicencia();
                    $intDiasIncapacidades = $arPeriodoEmpleado->getDiasIncapacidadGeneral() + $arPeriodoEmpleado->getDiasIncapacidadLaboral();
                    $intDiasLicenciaMaternidad = $arPeriodoEmpleado->getDiasLicenciaMaternidad();
                    $intDiasVacaciones = 0;

                    $intDiasCotizar = $arPeriodoEmpleado->getDias();
                    $intDiasCotizarPension = $intDiasLicenciaNoRemunerada;
                    $intDiasCotizarSalud = $intDiasLicenciaNoRemunerada;
                    $intDiasCotizarRiesgos = $intDiasLicenciaNoRemunerada;
                    $intDiasCotizarCaja = $intDiasLicenciaNoRemunerada;                                

                    if($intDiasCotizarPension > 0) {
                        $arAporte->setIngreso(' ');    
                    } else {
                        $arAporte->setIngreso($arPeriodoEmpleado->getIngreso());
                    }

                    if($intDiasCotizarPension > 0) {
                        $arAporte->setRetiro(' ');
                    } else {
                        $arAporte->setRetiro($arPeriodoEmpleado->getRetiro());
                    }                

                    if($arAporte->getTipoCotizante() == '19' || $arAporte->getTipoCotizante() == '12' || $arAporte->getTipoCotizante() == '23') {
                        $intDiasCotizarPension = 0;
                        $intDiasCotizarCaja = 0;
                    }       
                    if($arAporte->getTipoCotizante() == '12' || $arAporte->getTipoCotizante() == '19') {
                        $intDiasCotizarRiesgos = 0;
                    }                
                    $arAporte->setDiasCotizadosPension($intDiasCotizarPension);
                    $arAporte->setDiasCotizadosSalud($intDiasCotizarSalud);
                    $arAporte->setDiasCotizadosRiesgosProfesionales($intDiasCotizarRiesgos);
                    $arAporte->setDiasCotizadosCajaCompensacion($intDiasCotizarCaja);                        

                    //Ibc
                    $floIbcBrutoPension = (($intDiasCotizarPension) * ($floSalario / 30));
                    $floIbcBrutoSalud = (($intDiasCotizarSalud) * ($floSalario / 30));                    
                    $floIbcBrutoRiesgos = ($intDiasCotizarRiesgos * ($floSalario / 30));
                    $floIbcBrutoCaja = ($intDiasCotizarCaja * ($floSalario / 30));
                    $floIbcPension = $this->redondearIbc($intDiasCotizarPension, $floIbcBrutoPension);
                    $floIbcSalud = $this->redondearIbc($intDiasCotizarSalud, $floIbcBrutoSalud);
                    $floIbcRiesgos = $this->redondearIbc($intDiasCotizarRiesgos, $floIbcBrutoRiesgos);
                    $floIbcCaja = $this->redondearIbc($intDiasCotizarCaja, $floIbcBrutoCaja);
                    if($intDiasCotizarRiesgos <= 0) {
                        $floIbcRiesgos = 0;
                    }
                    $arAporte->setIbcPension($floIbcPension);
                    $arAporte->setIbcSalud($floIbcSalud);
                    $arAporte->setIbcRiesgosProfesionales($floIbcRiesgos);
                    $arAporte->setIbcCaja($floIbcCaja);                                    

                    $floTarifaPension = $arPeriodoEmpleado->getTarifaPension();   // se quito un porcentaje de 4% (+ 4)         
                    $floTarifaSalud = 0;
                    $floTarifaRiesgos = 0;
                    $floTarifaCaja = 0;
                    $arAporte->setTarifaPension($floTarifaPension);
                    $arAporte->setTarifaSalud($floTarifaSalud);
                    $arAporte->setTarifaRiesgos($floTarifaRiesgos);
                    $arAporte->setTarifaCaja($floTarifaCaja);            

                    $floCotizacionFSPSolidaridad = 0;
                    $floCotizacionFSPSubsistencia = 0;            
                    $floAporteVoluntarioFondoPensionesObligatorias = 0;
                    $floCotizacionVoluntariaFondoPensionesObligatorias = 0;

                    $floCotizacionPension = $this->redondearAporte($floSalario + $floSuplementario, $floIbcPension, $floTarifaPension, $intDiasCotizarPension);            
                    if($floSalario >= ($arConfiguracionNomina->getVrSalario() * 4)) {
                        $floCotizacionFSPSolidaridad = 0;
                        $floCotizacionFSPSubsistencia = 0;
                    }
                    
                    $floCotizacionSalud = $this->redondearAporte($floSalario + $floSuplementario, $floIbcSalud, $floTarifaSalud, $intDiasCotizarSalud);
                    $floCotizacionRiesgos = $this->redondearAporte($floSalario + $floSuplementario, $floIbcRiesgos, $floTarifaRiesgos, $intDiasCotizarRiesgos);
                    $floCotizacionCaja = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaCaja, $intDiasCotizarCaja);
                    $floTotalCotizacionFondos = $floAporteVoluntarioFondoPensionesObligatorias + $floCotizacionVoluntariaFondoPensionesObligatorias + $floCotizacionPension;
                    
                    $arAporte->setAporteVoluntarioFondoPensionesObligatorias($floAporteVoluntarioFondoPensionesObligatorias);
                    $arAporte->setCotizacionVoluntarioFondoPensionesObligatorias($floCotizacionVoluntariaFondoPensionesObligatorias);
                    $arAporte->setAportesFondoSolidaridadPensionalSolidaridad($floCotizacionFSPSolidaridad);
                    $arAporte->setAportesFondoSolidaridadPensionalSubsistencia($floCotizacionFSPSolidaridad);
                    $arAporte->setTotalCotizacionFondos($floTotalCotizacionFondos);
                    $arAporte->setCotizacionPension($floCotizacionPension);
                    $arAporte->setCotizacionSalud($floCotizacionSalud);
                    $arAporte->setCotizacionRiesgos($floCotizacionRiesgos);
                    $arAporte->setCotizacionCaja($floCotizacionCaja);   
                    $arAporte->setCentroTrabajoCodigoCt($arPeriodoEmpleado->getContratoRel()->getCodigoCentroCostoFk());
                    $totalCotizacion = $floTotalCotizacionFondos + $floCotizacionSalud + $floCotizacionRiesgos + $floCotizacionCaja + $floCotizacionIcbf+$floCotizacionSena;
                    $totalCotizacionGeneral += $totalCotizacion;
                    $arAporte->setTotalCotizacion($totalCotizacion);
                    $em->persist($arAporte);
                    $i++;                
                }
            }
        }
        $arPeriodoDetalle->setTotalCotizacion($totalCotizacionGeneral);
        $arPeriodoDetalle->setEstadoGenerado(1);
        $arPeriodoDetalle->setNumeroRegistros($i - 1);
        $arPeriodoDetalle->setNumeroEmpleados($intNumeroEmpleados);                
        $em->persist($arPeriodoDetalle);
        $em->flush();
        return true;
    }
    
    public function desgenerar($codigoPeriodoDetalle) {
        $em = $this->getEntityManager();
        $strSql = "DELETE FROM rhu_sso_aporte WHERE codigo_periodo_detalle_fk = " . $codigoPeriodoDetalle;
        $em->getConnection()->executeQuery($strSql); 
        $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
        $arPeriodoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);
        $arPeriodoDetalle->setEstadoGenerado(0);
        $arPeriodoDetalle->setNumeroRegistros(0);
        $arPeriodoDetalle->setTotalCotizacion(0);
        $em->persist($arPeriodoDetalle);
        $em->flush();
        return true;
    }    
    
    public function ibcMesAnterior($codigoEmpleado, $intMes, $intAnio) {
        $em = $this->getEntityManager();
        $floIbcMesAnterior = 0;
        /*
        $arSsoPila = new \Soga\NominaBundle\Entity\SsoPila();
        $arSsoPila = $em->getRepository('SogaNominaBundle:SsoPila')->findOneBy(array('numeroIdentificacion' => $strIdentificacion, 'anio' => $intAnio, 'mes' => $intMes - 1));
        if(count($arSsoPila) > 0) {
            $floIbcMesAnterior = $arSsoPila->getSalarioBasico() + $arSsoPila->getTiempoSuplementario();
        }
         * 
         */
        return $floIbcMesAnterior;        
    }    
    
    public function liquidarIncapacidadGeneral($floSalario, $floSalarioAnterior, $intDias) {
        $em = $this->getEntityManager();
        $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        if($floSalarioAnterior > 0) {
            $floSalario = $floSalarioAnterior;
        }                
        $floValorDia = $floSalario / 30;
        $floValorDiaSalarioMinimo = $arConfiguracionNomina->getVrSalario() / 30;
        $floIbcIncapacidad = 0;       
                
        if($floSalario <= $arConfiguracionNomina->getVrSalario()) {
            $floIbcIncapacidad = $intDias * $floValorDia;            
        }
        if($floSalario > $arConfiguracionNomina->getVrSalario() && $floSalario <= $arConfiguracionNomina->getVrSalario() * 1.5) {
            $floIbcIncapacidad = $intDias * $floValorDiaSalarioMinimo;            
        }
        if($floSalario > ($arConfiguracionNomina->getVrSalario() * 1.5)) {
            $floIbcIncapacidad = $intDias * $floValorDia; 
            $floIbcIncapacidad = ($floIbcIncapacidad * 66.67)/100;            
        }        
        
        return $floIbcIncapacidad;
    }    
    
    public function liquidarIncapacidadLaboral($floSalario, $floSalarioAnterior, $intDias) {
        if($floSalarioAnterior > 0) {
            $floSalario = $floSalarioAnterior;
        }                
        $floValorDia = $floSalario / 30;        
        $floIbcIncapacidad = $intDias * $floValorDia;         
        return $floIbcIncapacidad;
    }        
    
    public function redondearIbc($intDias, $floIbcBruto) {
        $em = $this->getEntityManager();
        $floIbc = 0;       
        $floIbcRedondedado = round($floIbcBruto, -3, PHP_ROUND_HALF_DOWN);
        $floIbcMinimo = $this->redondearIbcMinimo($intDias);
        $floResiduo = fmod($floIbcBruto, 1000);
        if($floIbcRedondedado < $floIbcMinimo) {
            if($floResiduo > 500) {
                $floIbc = intval($floIbcBruto/1000)*1000+1000;
            } else {
                $floIbc = $floIbcBruto;
            }
            $floIbc = ceil($floIbc);
        } else {
            $floIbc = $floIbcRedondedado;
        }

        return $floIbc;
    }

    public function redondearIbcMinimo ($intDias) {
        $em = $this->getEntityManager();
        $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        $floValorDia = $arConfiguracionNomina->getVrSalario() / 30;
        $floIbcBruto = intval($intDias * $floValorDia);
        return $floIbcBruto;
    }    
    
    public function redondearAporte($floIbcTotal, $floIbc, $floTarifa, $intDias) {
        $em = $this->getEntityManager();
        $floTarifa = $floTarifa / 100;
        $floIbcBruto = ($floIbcTotal / 30) * $intDias;        
        $floCotizacionRedondeada = round($floIbc * $floTarifa, -2, PHP_ROUND_HALF_DOWN);        
        $floCotizacionCalculada = $floIbcBruto * $floTarifa;
        $floCotizacionIBC = $floIbc * $floTarifa;
        $floResiduo = fmod($floCotizacionIBC, 100);
        $floCotizacionMinimo = $this->redondearAporteMinimo($floTarifa, $intDias);
        if($floCotizacionRedondeada < $floCotizacionMinimo) {
            if($floResiduo > 50) {
                $floCotizacionRedondeada = intval($floCotizacionIBC/100) * 100 + 100;
            } else {
                if($floCotizacionIBC - $floResiduo >= $floCotizacionCalculada) {
                    $floCotizacionRedondeada = $floCotizacionIBC - $floResiduo;
                } else {
                    $floCotizacionRedondeada = $floCotizacionIBC;
                }
            }

            if(round($floCotizacionRedondeada) >= $floCotizacionCalculada) {
                $floCotizacion = round($floCotizacionRedondeada);
            } else {
                $floCotizacion = ceil($floCotizacionRedondeada);                                
            }
        } else {
            $floCotizacion = $floCotizacionRedondeada;
        }
        return $floCotizacion;
    }

    public function redondearAporteMinimo($floTarifa, $intDias) {
        $em = $this->getEntityManager();
        $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        $floSalario = $arConfiguracionNomina->getVrSalario();
        $douValorDia = $floSalario / 30;
        $floIbcReal = $douValorDia * $intDias;
        if($intDias != 30) {
            $floIbcRedondeo = round($floIbcReal, -3, PHP_ROUND_HALF_DOWN);
            if($floIbcRedondeo > $floIbcReal) {
                $floIbc = ceil($floIbcRedondeo);
            } else {
                $floIbc = ceil($floIbcReal);
            }

        } else {
            $floIbc = $floSalario;
        }
        $douCotizacion = 0;
        $floCotizacionCalculada = $floIbcReal * $floTarifa;
        $floCotizacionIBC = $floIbc * $floTarifa;
        $floResiduo = fmod($floCotizacionIBC, 100);
        if($floResiduo > 50) {
            $floCotizacionRedondeada = intval($floCotizacionIBC/100) * 100 + 100;
        } else {
            if($floCotizacionIBC - $floResiduo >= $floCotizacionCalculada) {
                $floCotizacionRedondeada = $floCotizacionIBC - $floResiduo;
            } else {
                $floCotizacionRedondeada = $floCotizacionIBC;
            }
        }

        if(round($floCotizacionRedondeada) >= $floCotizacionCalculada) {
            $douCotizacion = round($floCotizacionRedondeada);
        } else {
            $douCotizacion = ceil($floCotizacionRedondeada);
        }
        return $douCotizacion;
    }    
}