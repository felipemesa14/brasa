<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_seleccion_requisito")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSeleccionRequisitoRepository")
 */
class RhuSeleccionRequisito
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_requisito_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSeleccionRequisitoPk;        
    
    /**
     * @ORM\Column(name="fecha", type="date")
     */ 
    
    private $fecha;                   
    
    /**     
     * @ORM\Column(name="nombre", type="string")
     */    
    
    private $nombre;           
                
    /**     
     * @ORM\Column(name="cantidad_solicitada", type="integer")
     */    
    private $cantidadSolicitida;
    
    /**
     * @ORM\Column(name="fecha_pruebas", type="datetime", nullable=true)
     */ 
    
    private $fechaPruebas;         
    
    /**
     * @ORM\Column(name="estado_abierto", type="boolean")
     */
    private $estadoAbierto = 0;            
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk;
    
    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;
    
    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer", nullable=true)
     */    
    private $codigoCargoFk;
    
    /**
     * @ORM\Column(name="edad_minima_maxima", type="string", length=20, nullable=true)
     */
    private $edadMinimaMaxima;
    
    /**
     * @ORM\Column(name="numero_hijos", type="integer", nullable=true)
     */
    private $numeroHijos;
    
    /**
     * @ORM\Column(name="codigo_estado_civil_fk", type="string", length=1, nullable=true)
     */
    private $codigoEstadoCivilFk;
    
    /**
     * @ORM\Column(name="codigo_estudio_tipo_fk", type="integer", length=4, nullable=true)
     */    
    private $codigoEstudioTipoFk;
    
    /**
     * @ORM\Column(name="codigo_sexo_fk", type="string", length=1, nullable=true)
     */
    private $codigoSexoFk;
    
    /**
     * @ORM\Column(name="codigo_religion_fk", type="string", length=20, nullable=true)
     */
    private $codigoReligionFk;
    
    /**
     * @ORM\Column(name="codigo_experiencia_fk", type="string", length=30, nullable=true)
     */
    private $codigoExperienciaFk;
    
    /**
     * @ORM\Column(name="codigo_disponibilidad_fk", type="string", length=30, nullable=true)
     */
    private $codigoDisponibilidadFk;
    
    /**
     * @ORM\Column(name="codigo_tipo_vehiculo_fk", type="string", length=2, nullable=true)
     */
    private $codigoTipoVehiculoFk;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="seleccionesRequisitosCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCargo", inversedBy="seleccionesRequisitosCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk", referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEstadoCivil", inversedBy="seleccionesRequisitosEstadoCivilRel")
     * @ORM\JoinColumn(name="codigo_estado_civil_fk", referencedColumnName="codigo_estado_civil_pk")
     */
    protected $estadoCivilRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuSeleccionesRequisitosCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleadoEstudioTipo", inversedBy="seleccionesRequisitosEmpleadoEstudioTipoRel")
     * @ORM\JoinColumn(name="codigo_estudio_tipo_fk", referencedColumnName="codigo_empleado_estudio_tipo_pk")
     */
    protected $estudioTipoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccion", mappedBy="seleccionRequisitoRel")
     */
    protected $seleccionesSeleccionRequisitoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuAspirante", mappedBy="seleccionRequisitoRel")
     */
    protected $aspirantesSeleccionRequisitoRel;
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->seleccionesSeleccionRequisitoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->aspirantesSeleccionRequisitoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSeleccionRequisitoPk
     *
     * @return integer
     */
    public function getCodigoSeleccionRequisitoPk()
    {
        return $this->codigoSeleccionRequisitoPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuSeleccionRequisito
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuSeleccionRequisito
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set cantidadSolicitida
     *
     * @param integer $cantidadSolicitida
     *
     * @return RhuSeleccionRequisito
     */
    public function setCantidadSolicitida($cantidadSolicitida)
    {
        $this->cantidadSolicitida = $cantidadSolicitida;

        return $this;
    }

    /**
     * Get cantidadSolicitida
     *
     * @return integer
     */
    public function getCantidadSolicitida()
    {
        return $this->cantidadSolicitida;
    }

    /**
     * Set fechaPruebas
     *
     * @param \DateTime $fechaPruebas
     *
     * @return RhuSeleccionRequisito
     */
    public function setFechaPruebas($fechaPruebas)
    {
        $this->fechaPruebas = $fechaPruebas;

        return $this;
    }

    /**
     * Get fechaPruebas
     *
     * @return \DateTime
     */
    public function getFechaPruebas()
    {
        return $this->fechaPruebas;
    }

    /**
     * Set estadoAbierto
     *
     * @param boolean $estadoAbierto
     *
     * @return RhuSeleccionRequisito
     */
    public function setEstadoAbierto($estadoAbierto)
    {
        $this->estadoAbierto = $estadoAbierto;

        return $this;
    }

    /**
     * Get estadoAbierto
     *
     * @return boolean
     */
    public function getEstadoAbierto()
    {
        return $this->estadoAbierto;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuSeleccionRequisito
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
     * Set codigoCiudadFk
     *
     * @param integer $codigoCiudadFk
     *
     * @return RhuSeleccionRequisito
     */
    public function setCodigoCiudadFk($codigoCiudadFk)
    {
        $this->codigoCiudadFk = $codigoCiudadFk;

        return $this;
    }

    /**
     * Get codigoCiudadFk
     *
     * @return integer
     */
    public function getCodigoCiudadFk()
    {
        return $this->codigoCiudadFk;
    }

    /**
     * Set codigoCargoFk
     *
     * @param integer $codigoCargoFk
     *
     * @return RhuSeleccionRequisito
     */
    public function setCodigoCargoFk($codigoCargoFk)
    {
        $this->codigoCargoFk = $codigoCargoFk;

        return $this;
    }

    /**
     * Get codigoCargoFk
     *
     * @return integer
     */
    public function getCodigoCargoFk()
    {
        return $this->codigoCargoFk;
    }

    /**
     * Set edadMinimaMaxima
     *
     * @param string $edadMinimaMaxima
     *
     * @return RhuSeleccionRequisito
     */
    public function setEdadMinimaMaxima($edadMinimaMaxima)
    {
        $this->edadMinimaMaxima = $edadMinimaMaxima;

        return $this;
    }

    /**
     * Get edadMinimaMaxima
     *
     * @return string
     */
    public function getEdadMinimaMaxima()
    {
        return $this->edadMinimaMaxima;
    }

    /**
     * Set numeroHijos
     *
     * @param integer $numeroHijos
     *
     * @return RhuSeleccionRequisito
     */
    public function setNumeroHijos($numeroHijos)
    {
        $this->numeroHijos = $numeroHijos;

        return $this;
    }

    /**
     * Get numeroHijos
     *
     * @return integer
     */
    public function getNumeroHijos()
    {
        return $this->numeroHijos;
    }

    /**
     * Set codigoEstadoCivilFk
     *
     * @param string $codigoEstadoCivilFk
     *
     * @return RhuSeleccionRequisito
     */
    public function setCodigoEstadoCivilFk($codigoEstadoCivilFk)
    {
        $this->codigoEstadoCivilFk = $codigoEstadoCivilFk;

        return $this;
    }

    /**
     * Get codigoEstadoCivilFk
     *
     * @return string
     */
    public function getCodigoEstadoCivilFk()
    {
        return $this->codigoEstadoCivilFk;
    }

    /**
     * Set codigoEstudioTipoFk
     *
     * @param integer $codigoEstudioTipoFk
     *
     * @return RhuSeleccionRequisito
     */
    public function setCodigoEstudioTipoFk($codigoEstudioTipoFk)
    {
        $this->codigoEstudioTipoFk = $codigoEstudioTipoFk;

        return $this;
    }

    /**
     * Get codigoEstudioTipoFk
     *
     * @return integer
     */
    public function getCodigoEstudioTipoFk()
    {
        return $this->codigoEstudioTipoFk;
    }

    /**
     * Set codigoSexoFk
     *
     * @param string $codigoSexoFk
     *
     * @return RhuSeleccionRequisito
     */
    public function setCodigoSexoFk($codigoSexoFk)
    {
        $this->codigoSexoFk = $codigoSexoFk;

        return $this;
    }

    /**
     * Get codigoSexoFk
     *
     * @return string
     */
    public function getCodigoSexoFk()
    {
        return $this->codigoSexoFk;
    }

    /**
     * Set codigoReligionFk
     *
     * @param string $codigoReligionFk
     *
     * @return RhuSeleccionRequisito
     */
    public function setCodigoReligionFk($codigoReligionFk)
    {
        $this->codigoReligionFk = $codigoReligionFk;

        return $this;
    }

    /**
     * Get codigoReligionFk
     *
     * @return string
     */
    public function getCodigoReligionFk()
    {
        return $this->codigoReligionFk;
    }

    /**
     * Set codigoTipoVehiculoFk
     *
     * @param string $codigoTipoVehiculoFk
     *
     * @return RhuSeleccionRequisito
     */
    public function setCodigoTipoVehiculoFk($codigoTipoVehiculoFk)
    {
        $this->codigoTipoVehiculoFk = $codigoTipoVehiculoFk;

        return $this;
    }

    /**
     * Get codigoTipoVehiculoFk
     *
     * @return string
     */
    public function getCodigoTipoVehiculoFk()
    {
        return $this->codigoTipoVehiculoFk;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuSeleccionRequisito
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

    /**
     * Set cargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargoRel
     *
     * @return RhuSeleccionRequisito
     */
    public function setCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargoRel = null)
    {
        $this->cargoRel = $cargoRel;

        return $this;
    }

    /**
     * Get cargoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCargo
     */
    public function getCargoRel()
    {
        return $this->cargoRel;
    }

    /**
     * Set estadoCivilRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEstadoCivil $estadoCivilRel
     *
     * @return RhuSeleccionRequisito
     */
    public function setEstadoCivilRel(\Brasa\RecursoHumanoBundle\Entity\RhuEstadoCivil $estadoCivilRel = null)
    {
        $this->estadoCivilRel = $estadoCivilRel;

        return $this;
    }

    /**
     * Get estadoCivilRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEstadoCivil
     */
    public function getEstadoCivilRel()
    {
        return $this->estadoCivilRel;
    }

    /**
     * Set ciudadRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel
     *
     * @return RhuSeleccionRequisito
     */
    public function setCiudadRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel = null)
    {
        $this->ciudadRel = $ciudadRel;

        return $this;
    }

    /**
     * Get ciudadRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudad
     */
    public function getCiudadRel()
    {
        return $this->ciudadRel;
    }

    /**
     * Set estudioTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudioTipo $estudioTipoRel
     *
     * @return RhuSeleccionRequisito
     */
    public function setEstudioTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudioTipo $estudioTipoRel = null)
    {
        $this->estudioTipoRel = $estudioTipoRel;

        return $this;
    }

    /**
     * Get estudioTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudioTipo
     */
    public function getEstudioTipoRel()
    {
        return $this->estudioTipoRel;
    }

    /**
     * Add seleccionesSeleccionRequisitoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesSeleccionRequisitoRel
     *
     * @return RhuSeleccionRequisito
     */
    public function addSeleccionesSeleccionRequisitoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesSeleccionRequisitoRel)
    {
        $this->seleccionesSeleccionRequisitoRel[] = $seleccionesSeleccionRequisitoRel;

        return $this;
    }

    /**
     * Remove seleccionesSeleccionRequisitoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesSeleccionRequisitoRel
     */
    public function removeSeleccionesSeleccionRequisitoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesSeleccionRequisitoRel)
    {
        $this->seleccionesSeleccionRequisitoRel->removeElement($seleccionesSeleccionRequisitoRel);
    }

    /**
     * Get seleccionesSeleccionRequisitoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesSeleccionRequisitoRel()
    {
        return $this->seleccionesSeleccionRequisitoRel;
    }

    /**
     * Add aspirantesSeleccionRequisitoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAspirante $aspirantesSeleccionRequisitoRel
     *
     * @return RhuSeleccionRequisito
     */
    public function addAspirantesSeleccionRequisitoRel(\Brasa\RecursoHumanoBundle\Entity\RhuAspirante $aspirantesSeleccionRequisitoRel)
    {
        $this->aspirantesSeleccionRequisitoRel[] = $aspirantesSeleccionRequisitoRel;

        return $this;
    }

    /**
     * Remove aspirantesSeleccionRequisitoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAspirante $aspirantesSeleccionRequisitoRel
     */
    public function removeAspirantesSeleccionRequisitoRel(\Brasa\RecursoHumanoBundle\Entity\RhuAspirante $aspirantesSeleccionRequisitoRel)
    {
        $this->aspirantesSeleccionRequisitoRel->removeElement($aspirantesSeleccionRequisitoRel);
    }

    /**
     * Get aspirantesSeleccionRequisitoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAspirantesSeleccionRequisitoRel()
    {
        return $this->aspirantesSeleccionRequisitoRel;
    }

    /**
     * Set codigoExperienciaFk
     *
     * @param string $codigoExperienciaFk
     *
     * @return RhuSeleccionRequisito
     */
    public function setCodigoExperienciaFk($codigoExperienciaFk)
    {
        $this->codigoExperienciaFk = $codigoExperienciaFk;

        return $this;
    }

    /**
     * Get codigoExperienciaFk
     *
     * @return string
     */
    public function getCodigoExperienciaFk()
    {
        return $this->codigoExperienciaFk;
    }

    /**
     * Set codigoDisponibilidadFk
     *
     * @param string $codigoDisponibilidadFk
     *
     * @return RhuSeleccionRequisito
     */
    public function setCodigoDisponibilidadFk($codigoDisponibilidadFk)
    {
        $this->codigoDisponibilidadFk = $codigoDisponibilidadFk;

        return $this;
    }

    /**
     * Get codigoDisponibilidadFk
     *
     * @return string
     */
    public function getCodigoDisponibilidadFk()
    {
        return $this->codigoDisponibilidadFk;
    }
}
