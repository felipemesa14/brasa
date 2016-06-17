<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_soporte_pago_periodo")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurSoportePagoPeriodoRepository")
 */
class TurSoportePagoPeriodo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_soporte_pago_periodo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSoportePagoPeriodoPk;            
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;        

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;            
    
    /**
     * @ORM\Column(name="recursos", type="integer")
     */    
    private $recursos = 0;    
    
    /**
     * @ORM\Column(name="vr_pago", type="float")
     */    
    private $vrPago = 0;    
    
    /**
     * @ORM\Column(name="festivos", type="integer")
     */    
    private $festivos = 0;    
    
    /**
     * @ORM\Column(name="dias_adicionales", type="integer")
     */    
    private $diasAdicionales = 0;    

    /**
     * @ORM\Column(name="dias_periodo", type="integer")
     */    
    private $diasPeriodo = 0;    
    
    /**   
     * Cuando el usuario activa descanso festivos le suma 8 horas por cada festivo  
     * @ORM\Column(name="descanso_festivo_fijo", type="boolean")
     */    
    private $descansoFestivoFijo = false;     
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk;    

    /**
     * @ORM\Column(name="codigo_recurso_grupo_fk", type="integer", nullable=true)
     */    
    private $codigoRecursoGrupoFk;
    
    /**     
     * @ORM\Column(name="estado_generado", type="boolean")
     */    
    private $estadoGenerado = false; 

    /**     
     * @ORM\Column(name="estado_aprobado", type="boolean")
     */    
    private $estadoAprobado = false;     
    
    /**     
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = false;    

    /**
     * @ORM\Column(name="dia_festivo_real", type="integer")
     */    
    private $diaFestivoReal = 0;    

    /**
     * @ORM\Column(name="dia_domingo_real", type="integer")
     */    
    private $diaDomingoReal = 0;    
    
    /**
     * @ORM\Column(name="dia_descanso", type="integer")
     */    
    private $diaDescanso = 0;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurCentroCosto", inversedBy="soportesPagosPeriodosCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TurRecursoGrupo", inversedBy="soportesPagosPeriodosRecursoGrupoRel")
     * @ORM\JoinColumn(name="codigo_recurso_grupo_fk", referencedColumnName="codigo_recurso_grupo_pk")
     */
    protected $recursoGrupoRel;    
    
   /**
     * @ORM\OneToMany(targetEntity="TurSoportePago", mappedBy="soportePagoPeriodoRel")
     */
    protected $soportesPagosSoportePagoPeriodoRel;    
    
   /**
     * @ORM\OneToMany(targetEntity="TurSoportePagoDetalle", mappedBy="soportePagoPeriodoRel")
     */
    protected $soportesPagosDetallesSoportePagoPeriodoRel;     
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->soportesPagosSoportePagoPeriodoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->soportesPagosDetallesSoportePagoPeriodoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSoportePagoPeriodoPk
     *
     * @return integer
     */
    public function getCodigoSoportePagoPeriodoPk()
    {
        return $this->codigoSoportePagoPeriodoPk;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return TurSoportePagoPeriodo
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
     * @return TurSoportePagoPeriodo
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
     * Set recursos
     *
     * @param integer $recursos
     *
     * @return TurSoportePagoPeriodo
     */
    public function setRecursos($recursos)
    {
        $this->recursos = $recursos;

        return $this;
    }

    /**
     * Get recursos
     *
     * @return integer
     */
    public function getRecursos()
    {
        return $this->recursos;
    }

    /**
     * Set festivos
     *
     * @param integer $festivos
     *
     * @return TurSoportePagoPeriodo
     */
    public function setFestivos($festivos)
    {
        $this->festivos = $festivos;

        return $this;
    }

    /**
     * Get festivos
     *
     * @return integer
     */
    public function getFestivos()
    {
        return $this->festivos;
    }

    /**
     * Set diasAdicionales
     *
     * @param integer $diasAdicionales
     *
     * @return TurSoportePagoPeriodo
     */
    public function setDiasAdicionales($diasAdicionales)
    {
        $this->diasAdicionales = $diasAdicionales;

        return $this;
    }

    /**
     * Get diasAdicionales
     *
     * @return integer
     */
    public function getDiasAdicionales()
    {
        return $this->diasAdicionales;
    }

    /**
     * Set diasPeriodo
     *
     * @param integer $diasPeriodo
     *
     * @return TurSoportePagoPeriodo
     */
    public function setDiasPeriodo($diasPeriodo)
    {
        $this->diasPeriodo = $diasPeriodo;

        return $this;
    }

    /**
     * Get diasPeriodo
     *
     * @return integer
     */
    public function getDiasPeriodo()
    {
        return $this->diasPeriodo;
    }

    /**
     * Set descansoFestivoFijo
     *
     * @param boolean $descansoFestivoFijo
     *
     * @return TurSoportePagoPeriodo
     */
    public function setDescansoFestivoFijo($descansoFestivoFijo)
    {
        $this->descansoFestivoFijo = $descansoFestivoFijo;

        return $this;
    }

    /**
     * Get descansoFestivoFijo
     *
     * @return boolean
     */
    public function getDescansoFestivoFijo()
    {
        return $this->descansoFestivoFijo;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return TurSoportePagoPeriodo
     */
    public function setCodigoCentroCostoFk($codigoCentroCostoFk)
    {
        $this->codigoCentroCostoFk = $codigoCentroCostoFk;

        return $this;
    }

    /**
     * Get codigoCentroCostoFk
     *
     * @return integer
     */
    public function getCodigoCentroCostoFk()
    {
        return $this->codigoCentroCostoFk;
    }

    /**
     * Set estadoGenerado
     *
     * @param boolean $estadoGenerado
     *
     * @return TurSoportePagoPeriodo
     */
    public function setEstadoGenerado($estadoGenerado)
    {
        $this->estadoGenerado = $estadoGenerado;

        return $this;
    }

    /**
     * Get estadoGenerado
     *
     * @return boolean
     */
    public function getEstadoGenerado()
    {
        return $this->estadoGenerado;
    }

    /**
     * Set estadoAprobado
     *
     * @param boolean $estadoAprobado
     *
     * @return TurSoportePagoPeriodo
     */
    public function setEstadoAprobado($estadoAprobado)
    {
        $this->estadoAprobado = $estadoAprobado;

        return $this;
    }

    /**
     * Get estadoAprobado
     *
     * @return boolean
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return TurSoportePagoPeriodo
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
     * Set diaFestivoReal
     *
     * @param integer $diaFestivoReal
     *
     * @return TurSoportePagoPeriodo
     */
    public function setDiaFestivoReal($diaFestivoReal)
    {
        $this->diaFestivoReal = $diaFestivoReal;

        return $this;
    }

    /**
     * Get diaFestivoReal
     *
     * @return integer
     */
    public function getDiaFestivoReal()
    {
        return $this->diaFestivoReal;
    }

    /**
     * Set diaDomingoReal
     *
     * @param integer $diaDomingoReal
     *
     * @return TurSoportePagoPeriodo
     */
    public function setDiaDomingoReal($diaDomingoReal)
    {
        $this->diaDomingoReal = $diaDomingoReal;

        return $this;
    }

    /**
     * Get diaDomingoReal
     *
     * @return integer
     */
    public function getDiaDomingoReal()
    {
        return $this->diaDomingoReal;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCentroCosto $centroCostoRel
     *
     * @return TurSoportePagoPeriodo
     */
    public function setCentroCostoRel(\Brasa\TurnoBundle\Entity\TurCentroCosto $centroCostoRel = null)
    {
        $this->centroCostoRel = $centroCostoRel;

        return $this;
    }

    /**
     * Get centroCostoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurCentroCosto
     */
    public function getCentroCostoRel()
    {
        return $this->centroCostoRel;
    }

    /**
     * Add soportesPagosSoportePagoPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePago $soportesPagosSoportePagoPeriodoRel
     *
     * @return TurSoportePagoPeriodo
     */
    public function addSoportesPagosSoportePagoPeriodoRel(\Brasa\TurnoBundle\Entity\TurSoportePago $soportesPagosSoportePagoPeriodoRel)
    {
        $this->soportesPagosSoportePagoPeriodoRel[] = $soportesPagosSoportePagoPeriodoRel;

        return $this;
    }

    /**
     * Remove soportesPagosSoportePagoPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePago $soportesPagosSoportePagoPeriodoRel
     */
    public function removeSoportesPagosSoportePagoPeriodoRel(\Brasa\TurnoBundle\Entity\TurSoportePago $soportesPagosSoportePagoPeriodoRel)
    {
        $this->soportesPagosSoportePagoPeriodoRel->removeElement($soportesPagosSoportePagoPeriodoRel);
    }

    /**
     * Get soportesPagosSoportePagoPeriodoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSoportesPagosSoportePagoPeriodoRel()
    {
        return $this->soportesPagosSoportePagoPeriodoRel;
    }

    /**
     * Add soportesPagosDetallesSoportePagoPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesSoportePagoPeriodoRel
     *
     * @return TurSoportePagoPeriodo
     */
    public function addSoportesPagosDetallesSoportePagoPeriodoRel(\Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesSoportePagoPeriodoRel)
    {
        $this->soportesPagosDetallesSoportePagoPeriodoRel[] = $soportesPagosDetallesSoportePagoPeriodoRel;

        return $this;
    }

    /**
     * Remove soportesPagosDetallesSoportePagoPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesSoportePagoPeriodoRel
     */
    public function removeSoportesPagosDetallesSoportePagoPeriodoRel(\Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesSoportePagoPeriodoRel)
    {
        $this->soportesPagosDetallesSoportePagoPeriodoRel->removeElement($soportesPagosDetallesSoportePagoPeriodoRel);
    }

    /**
     * Get soportesPagosDetallesSoportePagoPeriodoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSoportesPagosDetallesSoportePagoPeriodoRel()
    {
        return $this->soportesPagosDetallesSoportePagoPeriodoRel;
    }

    /**
     * Set vrPago
     *
     * @param float $vrPago
     *
     * @return TurSoportePagoPeriodo
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
     * Set diaDescanso
     *
     * @param integer $diaDescanso
     *
     * @return TurSoportePagoPeriodo
     */
    public function setDiaDescanso($diaDescanso)
    {
        $this->diaDescanso = $diaDescanso;

        return $this;
    }

    /**
     * Get diaDescanso
     *
     * @return integer
     */
    public function getDiaDescanso()
    {
        return $this->diaDescanso;
    }

    /**
     * Set codigoRecursoGrupoFk
     *
     * @param integer $codigoRecursoGrupoFk
     *
     * @return TurSoportePagoPeriodo
     */
    public function setCodigoRecursoGrupoFk($codigoRecursoGrupoFk)
    {
        $this->codigoRecursoGrupoFk = $codigoRecursoGrupoFk;

        return $this;
    }

    /**
     * Get codigoRecursoGrupoFk
     *
     * @return integer
     */
    public function getCodigoRecursoGrupoFk()
    {
        return $this->codigoRecursoGrupoFk;
    }

    /**
     * Set recursoGrupoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurRecursoGrupo $recursoGrupoRel
     *
     * @return TurSoportePagoPeriodo
     */
    public function setRecursoGrupoRel(\Brasa\TurnoBundle\Entity\TurRecursoGrupo $recursoGrupoRel = null)
    {
        $this->recursoGrupoRel = $recursoGrupoRel;

        return $this;
    }

    /**
     * Get recursoGrupoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurRecursoGrupo
     */
    public function getRecursoGrupoRel()
    {
        return $this->recursoGrupoRel;
    }
}
