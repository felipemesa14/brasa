<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_soporte_pago")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurSoportePagoRepository")
 */
class TurSoportePago
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_soporte_pago_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSoportePagoPk;         
    
    /**
     * @ORM\Column(name="codigo_soporte_pago_periodo_fk", type="integer", nullable=true)
     */    
    private $codigoSoportePagoPeriodoFk;     
    
    /**
     * @ORM\Column(name="codigo_recurso_fk", type="integer", nullable=true)
     */    
    private $codigoRecursoFk;    
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;        

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;            
    
    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */    
    private $codigoContratoFk;    
    
    /**
     * @ORM\Column(name="vr_salario", type="float")
     */    
    private $vrSalario = 0;    
    
    /**
     * @ORM\Column(name="vr_auxilio_transporte", type="float")
     */    
    private $vrAuxilioTransporte = 0;     
    
    /**
     * @ORM\Column(name="vr_pago", type="float")
     */    
    private $vrPago = 0;    
    
    /**     
     * @ORM\Column(name="estado_cerrado", type="boolean", nullable=true)
     */    
    private $estadoCerrado = false;    

    /**
     * @ORM\Column(name="descanso", type="float")
     */    
    private $descanso = 0;    

    /**
     * @ORM\Column(name="novedad", type="integer")
     */    
    private $novedad = 0;

    /**
     * @ORM\Column(name="incapacidad", type="integer")
     */    
    private $incapacidad = 0;
    
    /**
     * @ORM\Column(name="licencia", type="integer")
     */    
    private $licencia = 0;    
    
    /**
     * @ORM\Column(name="vacacion", type="integer")
     */    
    private $vacacion = 0;    
    
    /**
     * @ORM\Column(name="dias", type="float")
     */    
    private $dias = 0;    
    
    /**
     * @ORM\Column(name="horas", type="float")
     */    
    private $horas = 0;    
    
    /**
     * @ORM\Column(name="horas_pago", type="float")
     */    
    private $horasPago = 0;    
    
    /**
     * @ORM\Column(name="horas_descanso", type="float")
     */    
    private $horasDescanso = 0;    

    /**
     * @ORM\Column(name="horas_novedad", type="float")
     */    
    private $horasNovedad = 0;
    
    /**
     * @ORM\Column(name="horas_diurnas", type="float")
     */    
    private $horasDiurnas = 0;     

    /**
     * @ORM\Column(name="horas_nocturnas", type="float")
     */    
    private $horasNocturnas = 0;    
        
    /**
     * @ORM\Column(name="horas_festivas_diurnas", type="float")
     */    
    private $horasFestivasDiurnas = 0;     

    /**
     * @ORM\Column(name="horas_festivas_nocturnas", type="float")
     */    
    private $horasFestivasNocturnas = 0;     
    
    /**
     * @ORM\Column(name="horas_extras_ordinarias_diurnas", type="float")
     */    
    private $horasExtrasOrdinariasDiurnas = 0;    

    /**
     * @ORM\Column(name="horas_extras_ordinarias_nocturnas", type="float")
     */    
    private $horasExtrasOrdinariasNocturnas = 0;        

    /**
     * @ORM\Column(name="horas_extras_festivas_diurnas", type="float")
     */    
    private $horasExtrasFestivasDiurnas = 0;    

    /**
     * @ORM\Column(name="horas_extras_festivas_nocturnas", type="float")
     */    
    private $horasExtrasFestivasNocturnas = 0;    

    /**
     * @ORM\Column(name="horas_descanso_reales", type="float")
     */    
    private $horasDescansoReales = 0;    
    
    /**
     * @ORM\Column(name="horas_diurnas_reales", type="float")
     */    
    private $horasDiurnasReales = 0;     

    /**
     * @ORM\Column(name="horas_nocturnas_reales", type="float")
     */    
    private $horasNocturnasReales = 0;    
        
    /**
     * @ORM\Column(name="horas_festivas_diurnas_reales", type="float")
     */    
    private $horasFestivasDiurnasReales = 0;     

    /**
     * @ORM\Column(name="horas_festivas_nocturnas_reales", type="float")
     */    
    private $horasFestivasNocturnasReales = 0;     
    
    /**
     * @ORM\Column(name="horas_extras_ordinarias_diurnas_reales", type="float")
     */    
    private $horasExtrasOrdinariasDiurnasReales = 0;    

    /**
     * @ORM\Column(name="horas_extras_ordinarias_nocturnas_reales", type="float")
     */    
    private $horasExtrasOrdinariasNocturnasReales = 0;        

    /**
     * @ORM\Column(name="horas_extras_festivas_diurnas_reales", type="float")
     */    
    private $horasExtrasFestivasDiurnasReales = 0;    

    /**
     * @ORM\Column(name="horas_extras_festivas_nocturnas_reales", type="float")
     */    
    private $horasExtrasFestivasNocturnasReales = 0;    
    
    /**     
     * @ORM\Column(name="terminacion_turno", type="integer", nullable=true)
     */    
    private $terminacionTurno;     
    
    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;         
    
    /**
     * @ORM\ManyToOne(targetEntity="TurSoportePagoPeriodo", inversedBy="soportesPagosSoportePagoPeriodoRel")
     * @ORM\JoinColumn(name="codigo_soporte_pago_periodo_fk", referencedColumnName="codigo_soporte_pago_periodo_pk")
     */
    protected $soportePagoPeriodoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurRecurso", inversedBy="soportesPagosRecursoRel")
     * @ORM\JoinColumn(name="codigo_recurso_fk", referencedColumnName="codigo_recurso_pk")
     */
    protected $recursoRel;   
    
   /**
     * @ORM\OneToMany(targetEntity="TurSoportePagoDetalle", mappedBy="soportePagoRel", cascade={"persist", "remove"})
     */
    protected $soportesPagosDetallesSoportePagoRel;     


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->soportesPagosDetallesSoportePagoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSoportePagoPk
     *
     * @return integer
     */
    public function getCodigoSoportePagoPk()
    {
        return $this->codigoSoportePagoPk;
    }

    /**
     * Set codigoSoportePagoPeriodoFk
     *
     * @param integer $codigoSoportePagoPeriodoFk
     *
     * @return TurSoportePago
     */
    public function setCodigoSoportePagoPeriodoFk($codigoSoportePagoPeriodoFk)
    {
        $this->codigoSoportePagoPeriodoFk = $codigoSoportePagoPeriodoFk;

        return $this;
    }

    /**
     * Get codigoSoportePagoPeriodoFk
     *
     * @return integer
     */
    public function getCodigoSoportePagoPeriodoFk()
    {
        return $this->codigoSoportePagoPeriodoFk;
    }

    /**
     * Set codigoRecursoFk
     *
     * @param integer $codigoRecursoFk
     *
     * @return TurSoportePago
     */
    public function setCodigoRecursoFk($codigoRecursoFk)
    {
        $this->codigoRecursoFk = $codigoRecursoFk;

        return $this;
    }

    /**
     * Get codigoRecursoFk
     *
     * @return integer
     */
    public function getCodigoRecursoFk()
    {
        return $this->codigoRecursoFk;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return TurSoportePago
     */
    public function setFechaDesde($fechaDesde)
    {
        $this->fechaDesde = $fechaDesde;

        return $this;
    }

    /**
     * Get fechaDesde
     *
     * @return \DateTime
     */
    public function getFechaDesde()
    {
        return $this->fechaDesde;
    }

    /**
     * Set fechaHasta
     *
     * @param \DateTime $fechaHasta
     *
     * @return TurSoportePago
     */
    public function setFechaHasta($fechaHasta)
    {
        $this->fechaHasta = $fechaHasta;

        return $this;
    }

    /**
     * Get fechaHasta
     *
     * @return \DateTime
     */
    public function getFechaHasta()
    {
        return $this->fechaHasta;
    }

    /**
     * Set codigoContratoFk
     *
     * @param integer $codigoContratoFk
     *
     * @return TurSoportePago
     */
    public function setCodigoContratoFk($codigoContratoFk)
    {
        $this->codigoContratoFk = $codigoContratoFk;

        return $this;
    }

    /**
     * Get codigoContratoFk
     *
     * @return integer
     */
    public function getCodigoContratoFk()
    {
        return $this->codigoContratoFk;
    }

    /**
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return TurSoportePago
     */
    public function setVrSalario($vrSalario)
    {
        $this->vrSalario = $vrSalario;

        return $this;
    }

    /**
     * Get vrSalario
     *
     * @return float
     */
    public function getVrSalario()
    {
        return $this->vrSalario;
    }

    /**
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return TurSoportePago
     */
    public function setEstadoCerrado($estadoCerrado)
    {
        $this->estadoCerrado = $estadoCerrado;

        return $this;
    }

    /**
     * Get estadoCerrado
     *
     * @return boolean
     */
    public function getEstadoCerrado()
    {
        return $this->estadoCerrado;
    }

    /**
     * Set descanso
     *
     * @param float $descanso
     *
     * @return TurSoportePago
     */
    public function setDescanso($descanso)
    {
        $this->descanso = $descanso;

        return $this;
    }

    /**
     * Get descanso
     *
     * @return float
     */
    public function getDescanso()
    {
        return $this->descanso;
    }

    /**
     * Set novedad
     *
     * @param integer $novedad
     *
     * @return TurSoportePago
     */
    public function setNovedad($novedad)
    {
        $this->novedad = $novedad;

        return $this;
    }

    /**
     * Get novedad
     *
     * @return integer
     */
    public function getNovedad()
    {
        return $this->novedad;
    }

    /**
     * Set incapacidad
     *
     * @param integer $incapacidad
     *
     * @return TurSoportePago
     */
    public function setIncapacidad($incapacidad)
    {
        $this->incapacidad = $incapacidad;

        return $this;
    }

    /**
     * Get incapacidad
     *
     * @return integer
     */
    public function getIncapacidad()
    {
        return $this->incapacidad;
    }

    /**
     * Set licencia
     *
     * @param integer $licencia
     *
     * @return TurSoportePago
     */
    public function setLicencia($licencia)
    {
        $this->licencia = $licencia;

        return $this;
    }

    /**
     * Get licencia
     *
     * @return integer
     */
    public function getLicencia()
    {
        return $this->licencia;
    }

    /**
     * Set vacacion
     *
     * @param integer $vacacion
     *
     * @return TurSoportePago
     */
    public function setVacacion($vacacion)
    {
        $this->vacacion = $vacacion;

        return $this;
    }

    /**
     * Get vacacion
     *
     * @return integer
     */
    public function getVacacion()
    {
        return $this->vacacion;
    }

    /**
     * Set dias
     *
     * @param float $dias
     *
     * @return TurSoportePago
     */
    public function setDias($dias)
    {
        $this->dias = $dias;

        return $this;
    }

    /**
     * Get dias
     *
     * @return float
     */
    public function getDias()
    {
        return $this->dias;
    }

    /**
     * Set horas
     *
     * @param float $horas
     *
     * @return TurSoportePago
     */
    public function setHoras($horas)
    {
        $this->horas = $horas;

        return $this;
    }

    /**
     * Get horas
     *
     * @return float
     */
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * Set horasPago
     *
     * @param float $horasPago
     *
     * @return TurSoportePago
     */
    public function setHorasPago($horasPago)
    {
        $this->horasPago = $horasPago;

        return $this;
    }

    /**
     * Get horasPago
     *
     * @return float
     */
    public function getHorasPago()
    {
        return $this->horasPago;
    }

    /**
     * Set horasDescanso
     *
     * @param float $horasDescanso
     *
     * @return TurSoportePago
     */
    public function setHorasDescanso($horasDescanso)
    {
        $this->horasDescanso = $horasDescanso;

        return $this;
    }

    /**
     * Get horasDescanso
     *
     * @return float
     */
    public function getHorasDescanso()
    {
        return $this->horasDescanso;
    }

    /**
     * Set horasNovedad
     *
     * @param float $horasNovedad
     *
     * @return TurSoportePago
     */
    public function setHorasNovedad($horasNovedad)
    {
        $this->horasNovedad = $horasNovedad;

        return $this;
    }

    /**
     * Get horasNovedad
     *
     * @return float
     */
    public function getHorasNovedad()
    {
        return $this->horasNovedad;
    }

    /**
     * Set horasDiurnas
     *
     * @param float $horasDiurnas
     *
     * @return TurSoportePago
     */
    public function setHorasDiurnas($horasDiurnas)
    {
        $this->horasDiurnas = $horasDiurnas;

        return $this;
    }

    /**
     * Get horasDiurnas
     *
     * @return float
     */
    public function getHorasDiurnas()
    {
        return $this->horasDiurnas;
    }

    /**
     * Set horasNocturnas
     *
     * @param float $horasNocturnas
     *
     * @return TurSoportePago
     */
    public function setHorasNocturnas($horasNocturnas)
    {
        $this->horasNocturnas = $horasNocturnas;

        return $this;
    }

    /**
     * Get horasNocturnas
     *
     * @return float
     */
    public function getHorasNocturnas()
    {
        return $this->horasNocturnas;
    }

    /**
     * Set horasFestivasDiurnas
     *
     * @param float $horasFestivasDiurnas
     *
     * @return TurSoportePago
     */
    public function setHorasFestivasDiurnas($horasFestivasDiurnas)
    {
        $this->horasFestivasDiurnas = $horasFestivasDiurnas;

        return $this;
    }

    /**
     * Get horasFestivasDiurnas
     *
     * @return float
     */
    public function getHorasFestivasDiurnas()
    {
        return $this->horasFestivasDiurnas;
    }

    /**
     * Set horasFestivasNocturnas
     *
     * @param float $horasFestivasNocturnas
     *
     * @return TurSoportePago
     */
    public function setHorasFestivasNocturnas($horasFestivasNocturnas)
    {
        $this->horasFestivasNocturnas = $horasFestivasNocturnas;

        return $this;
    }

    /**
     * Get horasFestivasNocturnas
     *
     * @return float
     */
    public function getHorasFestivasNocturnas()
    {
        return $this->horasFestivasNocturnas;
    }

    /**
     * Set horasExtrasOrdinariasDiurnas
     *
     * @param float $horasExtrasOrdinariasDiurnas
     *
     * @return TurSoportePago
     */
    public function setHorasExtrasOrdinariasDiurnas($horasExtrasOrdinariasDiurnas)
    {
        $this->horasExtrasOrdinariasDiurnas = $horasExtrasOrdinariasDiurnas;

        return $this;
    }

    /**
     * Get horasExtrasOrdinariasDiurnas
     *
     * @return float
     */
    public function getHorasExtrasOrdinariasDiurnas()
    {
        return $this->horasExtrasOrdinariasDiurnas;
    }

    /**
     * Set horasExtrasOrdinariasNocturnas
     *
     * @param float $horasExtrasOrdinariasNocturnas
     *
     * @return TurSoportePago
     */
    public function setHorasExtrasOrdinariasNocturnas($horasExtrasOrdinariasNocturnas)
    {
        $this->horasExtrasOrdinariasNocturnas = $horasExtrasOrdinariasNocturnas;

        return $this;
    }

    /**
     * Get horasExtrasOrdinariasNocturnas
     *
     * @return float
     */
    public function getHorasExtrasOrdinariasNocturnas()
    {
        return $this->horasExtrasOrdinariasNocturnas;
    }

    /**
     * Set horasExtrasFestivasDiurnas
     *
     * @param float $horasExtrasFestivasDiurnas
     *
     * @return TurSoportePago
     */
    public function setHorasExtrasFestivasDiurnas($horasExtrasFestivasDiurnas)
    {
        $this->horasExtrasFestivasDiurnas = $horasExtrasFestivasDiurnas;

        return $this;
    }

    /**
     * Get horasExtrasFestivasDiurnas
     *
     * @return float
     */
    public function getHorasExtrasFestivasDiurnas()
    {
        return $this->horasExtrasFestivasDiurnas;
    }

    /**
     * Set horasExtrasFestivasNocturnas
     *
     * @param float $horasExtrasFestivasNocturnas
     *
     * @return TurSoportePago
     */
    public function setHorasExtrasFestivasNocturnas($horasExtrasFestivasNocturnas)
    {
        $this->horasExtrasFestivasNocturnas = $horasExtrasFestivasNocturnas;

        return $this;
    }

    /**
     * Get horasExtrasFestivasNocturnas
     *
     * @return float
     */
    public function getHorasExtrasFestivasNocturnas()
    {
        return $this->horasExtrasFestivasNocturnas;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return TurSoportePago
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set diaFestivo
     *
     * @param integer $diaFestivo
     *
     * @return TurSoportePago
     */
    public function setDiaFestivo($diaFestivo)
    {
        $this->diaFestivo = $diaFestivo;

        return $this;
    }

    /**
     * Get diaFestivo
     *
     * @return integer
     */
    public function getDiaFestivo()
    {
        return $this->diaFestivo;
    }

    /**
     * Set diaDomingo
     *
     * @param integer $diaDomingo
     *
     * @return TurSoportePago
     */
    public function setDiaDomingo($diaDomingo)
    {
        $this->diaDomingo = $diaDomingo;

        return $this;
    }

    /**
     * Get diaDomingo
     *
     * @return integer
     */
    public function getDiaDomingo()
    {
        return $this->diaDomingo;
    }

    /**
     * Set soportePagoPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo $soportePagoPeriodoRel
     *
     * @return TurSoportePago
     */
    public function setSoportePagoPeriodoRel(\Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo $soportePagoPeriodoRel = null)
    {
        $this->soportePagoPeriodoRel = $soportePagoPeriodoRel;

        return $this;
    }

    /**
     * Get soportePagoPeriodoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo
     */
    public function getSoportePagoPeriodoRel()
    {
        return $this->soportePagoPeriodoRel;
    }

    /**
     * Set recursoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurRecurso $recursoRel
     *
     * @return TurSoportePago
     */
    public function setRecursoRel(\Brasa\TurnoBundle\Entity\TurRecurso $recursoRel = null)
    {
        $this->recursoRel = $recursoRel;

        return $this;
    }

    /**
     * Get recursoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurRecurso
     */
    public function getRecursoRel()
    {
        return $this->recursoRel;
    }

    /**
     * Add soportesPagosDetallesSoportePagoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesSoportePagoRel
     *
     * @return TurSoportePago
     */
    public function addSoportesPagosDetallesSoportePagoRel(\Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesSoportePagoRel)
    {
        $this->soportesPagosDetallesSoportePagoRel[] = $soportesPagosDetallesSoportePagoRel;

        return $this;
    }

    /**
     * Remove soportesPagosDetallesSoportePagoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesSoportePagoRel
     */
    public function removeSoportesPagosDetallesSoportePagoRel(\Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesSoportePagoRel)
    {
        $this->soportesPagosDetallesSoportePagoRel->removeElement($soportesPagosDetallesSoportePagoRel);
    }

    /**
     * Get soportesPagosDetallesSoportePagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSoportesPagosDetallesSoportePagoRel()
    {
        return $this->soportesPagosDetallesSoportePagoRel;
    }

    /**
     * Set vrPago
     *
     * @param float $vrPago
     *
     * @return TurSoportePago
     */
    public function setVrPago($vrPago)
    {
        $this->vrPago = $vrPago;

        return $this;
    }

    /**
     * Get vrPago
     *
     * @return float
     */
    public function getVrPago()
    {
        return $this->vrPago;
    }

    /**
     * Set horasDescansoReales
     *
     * @param float $horasDescansoReales
     *
     * @return TurSoportePago
     */
    public function setHorasDescansoReales($horasDescansoReales)
    {
        $this->horasDescansoReales = $horasDescansoReales;

        return $this;
    }

    /**
     * Get horasDescansoReales
     *
     * @return float
     */
    public function getHorasDescansoReales()
    {
        return $this->horasDescansoReales;
    }

    /**
     * Set horasDiurnasReales
     *
     * @param float $horasDiurnasReales
     *
     * @return TurSoportePago
     */
    public function setHorasDiurnasReales($horasDiurnasReales)
    {
        $this->horasDiurnasReales = $horasDiurnasReales;

        return $this;
    }

    /**
     * Get horasDiurnasReales
     *
     * @return float
     */
    public function getHorasDiurnasReales()
    {
        return $this->horasDiurnasReales;
    }

    /**
     * Set horasNocturnasReales
     *
     * @param float $horasNocturnasReales
     *
     * @return TurSoportePago
     */
    public function setHorasNocturnasReales($horasNocturnasReales)
    {
        $this->horasNocturnasReales = $horasNocturnasReales;

        return $this;
    }

    /**
     * Get horasNocturnasReales
     *
     * @return float
     */
    public function getHorasNocturnasReales()
    {
        return $this->horasNocturnasReales;
    }

    /**
     * Set horasFestivasDiurnasReales
     *
     * @param float $horasFestivasDiurnasReales
     *
     * @return TurSoportePago
     */
    public function setHorasFestivasDiurnasReales($horasFestivasDiurnasReales)
    {
        $this->horasFestivasDiurnasReales = $horasFestivasDiurnasReales;

        return $this;
    }

    /**
     * Get horasFestivasDiurnasReales
     *
     * @return float
     */
    public function getHorasFestivasDiurnasReales()
    {
        return $this->horasFestivasDiurnasReales;
    }

    /**
     * Set horasFestivasNocturnasReales
     *
     * @param float $horasFestivasNocturnasReales
     *
     * @return TurSoportePago
     */
    public function setHorasFestivasNocturnasReales($horasFestivasNocturnasReales)
    {
        $this->horasFestivasNocturnasReales = $horasFestivasNocturnasReales;

        return $this;
    }

    /**
     * Get horasFestivasNocturnasReales
     *
     * @return float
     */
    public function getHorasFestivasNocturnasReales()
    {
        return $this->horasFestivasNocturnasReales;
    }

    /**
     * Set horasExtrasOrdinariasDiurnasReales
     *
     * @param float $horasExtrasOrdinariasDiurnasReales
     *
     * @return TurSoportePago
     */
    public function setHorasExtrasOrdinariasDiurnasReales($horasExtrasOrdinariasDiurnasReales)
    {
        $this->horasExtrasOrdinariasDiurnasReales = $horasExtrasOrdinariasDiurnasReales;

        return $this;
    }

    /**
     * Get horasExtrasOrdinariasDiurnasReales
     *
     * @return float
     */
    public function getHorasExtrasOrdinariasDiurnasReales()
    {
        return $this->horasExtrasOrdinariasDiurnasReales;
    }

    /**
     * Set horasExtrasOrdinariasNocturnasReales
     *
     * @param float $horasExtrasOrdinariasNocturnasReales
     *
     * @return TurSoportePago
     */
    public function setHorasExtrasOrdinariasNocturnasReales($horasExtrasOrdinariasNocturnasReales)
    {
        $this->horasExtrasOrdinariasNocturnasReales = $horasExtrasOrdinariasNocturnasReales;

        return $this;
    }

    /**
     * Get horasExtrasOrdinariasNocturnasReales
     *
     * @return float
     */
    public function getHorasExtrasOrdinariasNocturnasReales()
    {
        return $this->horasExtrasOrdinariasNocturnasReales;
    }

    /**
     * Set horasExtrasFestivasDiurnasReales
     *
     * @param float $horasExtrasFestivasDiurnasReales
     *
     * @return TurSoportePago
     */
    public function setHorasExtrasFestivasDiurnasReales($horasExtrasFestivasDiurnasReales)
    {
        $this->horasExtrasFestivasDiurnasReales = $horasExtrasFestivasDiurnasReales;

        return $this;
    }

    /**
     * Get horasExtrasFestivasDiurnasReales
     *
     * @return float
     */
    public function getHorasExtrasFestivasDiurnasReales()
    {
        return $this->horasExtrasFestivasDiurnasReales;
    }

    /**
     * Set horasExtrasFestivasNocturnasReales
     *
     * @param float $horasExtrasFestivasNocturnasReales
     *
     * @return TurSoportePago
     */
    public function setHorasExtrasFestivasNocturnasReales($horasExtrasFestivasNocturnasReales)
    {
        $this->horasExtrasFestivasNocturnasReales = $horasExtrasFestivasNocturnasReales;

        return $this;
    }

    /**
     * Get horasExtrasFestivasNocturnasReales
     *
     * @return float
     */
    public function getHorasExtrasFestivasNocturnasReales()
    {
        return $this->horasExtrasFestivasNocturnasReales;
    }

    /**
     * Set terminacionTurno
     *
     * @param integer $terminacionTurno
     *
     * @return TurSoportePago
     */
    public function setTerminacionTurno($terminacionTurno)
    {
        $this->terminacionTurno = $terminacionTurno;

        return $this;
    }

    /**
     * Get terminacionTurno
     *
     * @return integer
     */
    public function getTerminacionTurno()
    {
        return $this->terminacionTurno;
    }

    /**
     * Set vrAuxilioTransporte
     *
     * @param float $vrAuxilioTransporte
     *
     * @return TurSoportePago
     */
    public function setVrAuxilioTransporte($vrAuxilioTransporte)
    {
        $this->vrAuxilioTransporte = $vrAuxilioTransporte;

        return $this;
    }

    /**
     * Get vrAuxilioTransporte
     *
     * @return float
     */
    public function getVrAuxilioTransporte()
    {
        return $this->vrAuxilioTransporte;
    }
}
