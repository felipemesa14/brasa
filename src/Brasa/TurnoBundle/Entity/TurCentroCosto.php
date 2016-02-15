<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_centro_costo")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurCentroCostoRepository")
 */
class TurCentroCosto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_centro_costo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCentroCostoPk;                    
    
    /**
     * @ORM\Column(name="nombre", type="string", length=100)
     */
    private $nombre;                         
    
    /**
     * @ORM\OneToMany(targetEntity="TurRecurso", mappedBy="centroCostoRel")
     */
    protected $recursosCentroCostoRel; 
   
    /**
     * @ORM\OneToMany(targetEntity="TurCentroCosto", mappedBy="centroCostoRel")
     */
    protected $soportesPagosCentroCostoRel;     
    
    /**
     * Get codigoCentroCostoPk
     *
     * @return integer
     */
    public function getCodigoCentroCostoPk()
    {
        return $this->codigoCentroCostoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurCentroCosto
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
     * Constructor
     */
    public function __construct()
    {
        $this->recursosCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add recursosCentroCostoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurRecurso $recursosCentroCostoRel
     *
     * @return TurCentroCosto
     */
    public function addRecursosCentroCostoRel(\Brasa\TurnoBundle\Entity\TurRecurso $recursosCentroCostoRel)
    {
        $this->recursosCentroCostoRel[] = $recursosCentroCostoRel;

        return $this;
    }

    /**
     * Remove recursosCentroCostoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurRecurso $recursosCentroCostoRel
     */
    public function removeRecursosCentroCostoRel(\Brasa\TurnoBundle\Entity\TurRecurso $recursosCentroCostoRel)
    {
        $this->recursosCentroCostoRel->removeElement($recursosCentroCostoRel);
    }

    /**
     * Get recursosCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecursosCentroCostoRel()
    {
        return $this->recursosCentroCostoRel;
    }

    /**
     * Add soportesPagosCentroCostoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCentroCosto $soportesPagosCentroCostoRel
     *
     * @return TurCentroCosto
     */
    public function addSoportesPagosCentroCostoRel(\Brasa\TurnoBundle\Entity\TurCentroCosto $soportesPagosCentroCostoRel)
    {
        $this->soportesPagosCentroCostoRel[] = $soportesPagosCentroCostoRel;

        return $this;
    }

    /**
     * Remove soportesPagosCentroCostoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCentroCosto $soportesPagosCentroCostoRel
     */
    public function removeSoportesPagosCentroCostoRel(\Brasa\TurnoBundle\Entity\TurCentroCosto $soportesPagosCentroCostoRel)
    {
        $this->soportesPagosCentroCostoRel->removeElement($soportesPagosCentroCostoRel);
    }

    /**
     * Get soportesPagosCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSoportesPagosCentroCostoRel()
    {
        return $this->soportesPagosCentroCostoRel;
    }
}