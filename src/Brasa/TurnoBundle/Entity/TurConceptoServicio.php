<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_concepto_servicio")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurConceptoServicioRepository")
 */
class TurConceptoServicio
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_concepto_servicio_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoConceptoServicioPk;               
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */    
    private $nombre;             
    
    /**
     * @ORM\Column(name="horas", type="float")
     */    
    private $horas = 0;    

    /**
     * @ORM\Column(name="horas_diurnas", type="float")
     */    
    private $horasDiurnas = 0;     
    
    /**
     * @ORM\Column(name="horas_nocturnas", type="float")
     */    
    private $horasNocturnas = 0; 
    
    /**
     * @ORM\Column(name="vr_costo", type="float")
     */
    private $vrCosto = 0;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalle", mappedBy="conceptoServicioRel")
     */
    protected $pedidosDetallesConceptoServicioRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurServicioDetalle", mappedBy="conceptoServicioRel")
     */
    protected $serviciosDetallesConceptoServicioRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurCotizacionDetalle", mappedBy="conceptoServicioRel")
     */
    protected $cotizacionesDetallesConceptoServicioRel;     


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pedidosDetallesConceptoServicioRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviciosDetallesConceptoServicioRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cotizacionesDetallesConceptoServicioRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoConceptoServicioPk
     *
     * @return integer
     */
    public function getCodigoConceptoServicioPk()
    {
        return $this->codigoConceptoServicioPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurConceptoServicio
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
     * Set horas
     *
     * @param float $horas
     *
     * @return TurConceptoServicio
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
     * Set horasDiurnas
     *
     * @param float $horasDiurnas
     *
     * @return TurConceptoServicio
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
     * @return TurConceptoServicio
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
     * Set vrCosto
     *
     * @param float $vrCosto
     *
     * @return TurConceptoServicio
     */
    public function setVrCosto($vrCosto)
    {
        $this->vrCosto = $vrCosto;

        return $this;
    }

    /**
     * Get vrCosto
     *
     * @return float
     */
    public function getVrCosto()
    {
        return $this->vrCosto;
    }

    /**
     * Add pedidosDetallesConceptoServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesConceptoServicioRel
     *
     * @return TurConceptoServicio
     */
    public function addPedidosDetallesConceptoServicioRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesConceptoServicioRel)
    {
        $this->pedidosDetallesConceptoServicioRel[] = $pedidosDetallesConceptoServicioRel;

        return $this;
    }

    /**
     * Remove pedidosDetallesConceptoServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesConceptoServicioRel
     */
    public function removePedidosDetallesConceptoServicioRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesConceptoServicioRel)
    {
        $this->pedidosDetallesConceptoServicioRel->removeElement($pedidosDetallesConceptoServicioRel);
    }

    /**
     * Get pedidosDetallesConceptoServicioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDetallesConceptoServicioRel()
    {
        return $this->pedidosDetallesConceptoServicioRel;
    }

    /**
     * Add serviciosDetallesConceptoServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesConceptoServicioRel
     *
     * @return TurConceptoServicio
     */
    public function addServiciosDetallesConceptoServicioRel(\Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesConceptoServicioRel)
    {
        $this->serviciosDetallesConceptoServicioRel[] = $serviciosDetallesConceptoServicioRel;

        return $this;
    }

    /**
     * Remove serviciosDetallesConceptoServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesConceptoServicioRel
     */
    public function removeServiciosDetallesConceptoServicioRel(\Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesConceptoServicioRel)
    {
        $this->serviciosDetallesConceptoServicioRel->removeElement($serviciosDetallesConceptoServicioRel);
    }

    /**
     * Get serviciosDetallesConceptoServicioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosDetallesConceptoServicioRel()
    {
        return $this->serviciosDetallesConceptoServicioRel;
    }

    /**
     * Add cotizacionesDetallesConceptoServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesConceptoServicioRel
     *
     * @return TurConceptoServicio
     */
    public function addCotizacionesDetallesConceptoServicioRel(\Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesConceptoServicioRel)
    {
        $this->cotizacionesDetallesConceptoServicioRel[] = $cotizacionesDetallesConceptoServicioRel;

        return $this;
    }

    /**
     * Remove cotizacionesDetallesConceptoServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesConceptoServicioRel
     */
    public function removeCotizacionesDetallesConceptoServicioRel(\Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesConceptoServicioRel)
    {
        $this->cotizacionesDetallesConceptoServicioRel->removeElement($cotizacionesDetallesConceptoServicioRel);
    }

    /**
     * Get cotizacionesDetallesConceptoServicioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCotizacionesDetallesConceptoServicioRel()
    {
        return $this->cotizacionesDetallesConceptoServicioRel;
    }
}
