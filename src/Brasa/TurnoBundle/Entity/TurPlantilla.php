<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_plantilla")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurPlantillaRepository")
 */
class TurPlantilla
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_plantilla_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPlantillaPk;       

    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true)
     */    
    private $nombre;     
    
    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = false;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;         
    
    /**
     * @ORM\OneToMany(targetEntity="TurPlantillaDetalle", mappedBy="plantillaRel", cascade={"persist", "remove"})
     */
    protected $plantillasDetallesPlantillaRel; 


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->plantillasDetallesPlantillaRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPlantillaPk
     *
     * @return integer
     */
    public function getCodigoPlantillaPk()
    {
        return $this->codigoPlantillaPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurPlantilla
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TurPlantilla
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
     * Add plantillasDetallesPlantillaRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPlantillaDetalle $plantillasDetallesPlantillaRel
     *
     * @return TurPlantilla
     */
    public function addPlantillasDetallesPlantillaRel(\Brasa\TurnoBundle\Entity\TurPlantillaDetalle $plantillasDetallesPlantillaRel)
    {
        $this->plantillasDetallesPlantillaRel[] = $plantillasDetallesPlantillaRel;

        return $this;
    }

    /**
     * Remove plantillasDetallesPlantillaRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPlantillaDetalle $plantillasDetallesPlantillaRel
     */
    public function removePlantillasDetallesPlantillaRel(\Brasa\TurnoBundle\Entity\TurPlantillaDetalle $plantillasDetallesPlantillaRel)
    {
        $this->plantillasDetallesPlantillaRel->removeElement($plantillasDetallesPlantillaRel);
    }

    /**
     * Get plantillasDetallesPlantillaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlantillasDetallesPlantillaRel()
    {
        return $this->plantillasDetallesPlantillaRel;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return TurPlantilla
     */
    public function setEstadoAutorizado($estadoAutorizado)
    {
        $this->estadoAutorizado = $estadoAutorizado;

        return $this;
    }

    /**
     * Get estadoAutorizado
     *
     * @return boolean
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }
}
