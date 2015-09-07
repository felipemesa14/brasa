<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_examen_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuExamenTipoRepository")
 */
class RhuExamenTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_examen_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoExamenTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;            
    
    /**
     * @ORM\OneToMany(targetEntity="RhuExamenDetalle", mappedBy="examenTipoRel")
     */
    protected $examenesDetallesExamenTipoRel;
      
   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->examenesDetallesExamenTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoExamenTipoPk
     *
     * @return integer
     */
    public function getCodigoExamenTipoPk()
    {
        return $this->codigoExamenTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuExamenTipo
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
     * Add examenesDetallesExamenTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle $examenesDetallesExamenTipoRel
     *
     * @return RhuExamenTipo
     */
    public function addExamenesDetallesExamenTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle $examenesDetallesExamenTipoRel)
    {
        $this->examenesDetallesExamenTipoRel[] = $examenesDetallesExamenTipoRel;

        return $this;
    }

    /**
     * Remove examenesDetallesExamenTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle $examenesDetallesExamenTipoRel
     */
    public function removeExamenesDetallesExamenTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle $examenesDetallesExamenTipoRel)
    {
        $this->examenesDetallesExamenTipoRel->removeElement($examenesDetallesExamenTipoRel);
    }

    /**
     * Get examenesDetallesExamenTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExamenesDetallesExamenTipoRel()
    {
        return $this->examenesDetallesExamenTipoRel;
    }
}
