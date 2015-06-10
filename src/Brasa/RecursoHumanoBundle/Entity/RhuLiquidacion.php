<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_liquidacion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuLiquidacionRepository")
 */
class RhuLiquidacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_liquidacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoLiquidacionPk;            

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;               
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer")
     */    
    private $codigoCentroCostoFk;         
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;    
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta; 
    
    /**
     * @ORM\Column(name="numero_dias", type="string", length=30, nullable=true)
     */    
    private $numeroDias;                 
    
    /**
     * @ORM\Column(name="vr_cesantias", type="float")
     */
    private $VrCesantias = 0;    

    /**
     * @ORM\Column(name="vr_intereses_cesantias", type="float")
     */
    private $VrInteresesCesantias = 0;        

    /**
     * @ORM\Column(name="vr_prima", type="float")
     */
    private $VrPrima = 0;    

    /**
     * @ORM\Column(name="vr_vacaciones", type="float")
     */
    private $VrVacaciones = 0;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;        
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="liquidacionesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;        
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="liquidacionesCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;     



    /**
     * Get codigoLiquidacionPk
     *
     * @return integer
     */
    public function getCodigoLiquidacionPk()
    {
        return $this->codigoLiquidacionPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuLiquidacion
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuLiquidacion
     */
    public function setCodigoEmpleadoFk($codigoEmpleadoFk)
    {
        $this->codigoEmpleadoFk = $codigoEmpleadoFk;

        return $this;
    }

    /**
     * Get codigoEmpleadoFk
     *
     * @return integer
     */
    public function getCodigoEmpleadoFk()
    {
        return $this->codigoEmpleadoFk;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuLiquidacion
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
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuLiquidacion
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
     * @return RhuLiquidacion
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
     * Set numeroDias
     *
     * @param string $numeroDias
     *
     * @return RhuLiquidacion
     */
    public function setNumeroDias($numeroDias)
    {
        $this->numeroDias = $numeroDias;

        return $this;
    }

    /**
     * Get numeroDias
     *
     * @return string
     */
    public function getNumeroDias()
    {
        return $this->numeroDias;
    }

    /**
     * Set vrCesantias
     *
     * @param float $vrCesantias
     *
     * @return RhuLiquidacion
     */
    public function setVrCesantias($vrCesantias)
    {
        $this->VrCesantias = $vrCesantias;

        return $this;
    }

    /**
     * Get vrCesantias
     *
     * @return float
     */
    public function getVrCesantias()
    {
        return $this->VrCesantias;
    }

    /**
     * Set vrInteresesCesantias
     *
     * @param float $vrInteresesCesantias
     *
     * @return RhuLiquidacion
     */
    public function setVrInteresesCesantias($vrInteresesCesantias)
    {
        $this->VrInteresesCesantias = $vrInteresesCesantias;

        return $this;
    }

    /**
     * Get vrInteresesCesantias
     *
     * @return float
     */
    public function getVrInteresesCesantias()
    {
        return $this->VrInteresesCesantias;
    }

    /**
     * Set vrPrima
     *
     * @param float $vrPrima
     *
     * @return RhuLiquidacion
     */
    public function setVrPrima($vrPrima)
    {
        $this->VrPrima = $vrPrima;

        return $this;
    }

    /**
     * Get vrPrima
     *
     * @return float
     */
    public function getVrPrima()
    {
        return $this->VrPrima;
    }

    /**
     * Set vrVacaciones
     *
     * @param float $vrVacaciones
     *
     * @return RhuLiquidacion
     */
    public function setVrVacaciones($vrVacaciones)
    {
        $this->VrVacaciones = $vrVacaciones;

        return $this;
    }

    /**
     * Get vrVacaciones
     *
     * @return float
     */
    public function getVrVacaciones()
    {
        return $this->VrVacaciones;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuLiquidacion
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuLiquidacion
     */
    public function setEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel = null)
    {
        $this->empleadoRel = $empleadoRel;

        return $this;
    }

    /**
     * Get empleadoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado
     */
    public function getEmpleadoRel()
    {
        return $this->empleadoRel;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuLiquidacion
     */
    public function setCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel = null)
    {
        $this->centroCostoRel = $centroCostoRel;

        return $this;
    }

    /**
     * Get centroCostoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto
     */
    public function getCentroCostoRel()
    {
        return $this->centroCostoRel;
    }
}
