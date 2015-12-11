<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_turno")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurTurnoRepository")
 */
class TurTurno
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_turno_pk", type="string", length=5)
     */
    private $codigoTurnoPk;       
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\Column(name="hora_desde", type="time", nullable=true)
     */    
    private $horaDesde;    

    /**
     * @ORM\Column(name="hora_hasta", type="time", nullable=true)
     */    
    private $horaHasta;    
    
    /**
     * @ORM\Column(name="horas", type="integer")
     */    
    private $horas = 0;    

    /**
     * @ORM\Column(name="horas_diurnas", type="integer")
     */    
    private $horasDiurnas = 0;     
    
    /**
     * @ORM\Column(name="horas_nocturnas", type="integer")
     */    
    private $horasNocturnas = 0;    
    
    /**
     * @ORM\Column(name="servicio", type="boolean")
     */    
    private $servicio = false;     

    /**
     * @ORM\Column(name="programacion", type="boolean")
     */    
    private $programacion = false;    
    
    /**
     * @ORM\Column(name="novedad", type="boolean")
     */    
    private $novedad = false;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;       

    /**
     * @ORM\OneToMany(targetEntity="TurTurnoDetalle", mappedBy="turnoRel")
     */
    protected $turnosDetallesTurnoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalle", mappedBy="turnoRel")
     */
    protected $pedidosDetallesTurnoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurCotizacionDetalle", mappedBy="turnoRel")
     */
    protected $cotizacionesDetallesTurnoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurSoportePagoDetalle", mappedBy="turnoRel")
     */
    protected $soportesPagosDetallesTurnoRel;    
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->turnosDetallesTurnoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pedidosDetallesTurnoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cotizacionesDetallesTurnoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->soportesPagosDetallesTurnoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set codigoTurnoPk
     *
     * @param string $codigoTurnoPk
     *
     * @return TurTurno
     */
    public function setCodigoTurnoPk($codigoTurnoPk)
    {
        $this->codigoTurnoPk = $codigoTurnoPk;

        return $this;
    }

    /**
     * Get codigoTurnoPk
     *
     * @return string
     */
    public function getCodigoTurnoPk()
    {
        return $this->codigoTurnoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurTurno
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
     * Set horaDesde
     *
     * @param \DateTime $horaDesde
     *
     * @return TurTurno
     */
    public function setHoraDesde($horaDesde)
    {
        $this->horaDesde = $horaDesde;

        return $this;
    }

    /**
     * Get horaDesde
     *
     * @return \DateTime
     */
    public function getHoraDesde()
    {
        return $this->horaDesde;
    }

    /**
     * Set horaHasta
     *
     * @param \DateTime $horaHasta
     *
     * @return TurTurno
     */
    public function setHoraHasta($horaHasta)
    {
        $this->horaHasta = $horaHasta;

        return $this;
    }

    /**
     * Get horaHasta
     *
     * @return \DateTime
     */
    public function getHoraHasta()
    {
        return $this->horaHasta;
    }

    /**
     * Set horas
     *
     * @param integer $horas
     *
     * @return TurTurno
     */
    public function setHoras($horas)
    {
        $this->horas = $horas;

        return $this;
    }

    /**
     * Get horas
     *
     * @return integer
     */
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * Set horasDiurnas
     *
     * @param integer $horasDiurnas
     *
     * @return TurTurno
     */
    public function setHorasDiurnas($horasDiurnas)
    {
        $this->horasDiurnas = $horasDiurnas;

        return $this;
    }

    /**
     * Get horasDiurnas
     *
     * @return integer
     */
    public function getHorasDiurnas()
    {
        return $this->horasDiurnas;
    }

    /**
     * Set horasNocturnas
     *
     * @param integer $horasNocturnas
     *
     * @return TurTurno
     */
    public function setHorasNocturnas($horasNocturnas)
    {
        $this->horasNocturnas = $horasNocturnas;

        return $this;
    }

    /**
     * Get horasNocturnas
     *
     * @return integer
     */
    public function getHorasNocturnas()
    {
        return $this->horasNocturnas;
    }

    /**
     * Set servicio
     *
     * @param boolean $servicio
     *
     * @return TurTurno
     */
    public function setServicio($servicio)
    {
        $this->servicio = $servicio;

        return $this;
    }

    /**
     * Get servicio
     *
     * @return boolean
     */
    public function getServicio()
    {
        return $this->servicio;
    }

    /**
     * Set programacion
     *
     * @param boolean $programacion
     *
     * @return TurTurno
     */
    public function setProgramacion($programacion)
    {
        $this->programacion = $programacion;

        return $this;
    }

    /**
     * Get programacion
     *
     * @return boolean
     */
    public function getProgramacion()
    {
        return $this->programacion;
    }

    /**
     * Set novedad
     *
     * @param boolean $novedad
     *
     * @return TurTurno
     */
    public function setNovedad($novedad)
    {
        $this->novedad = $novedad;

        return $this;
    }

    /**
     * Get novedad
     *
     * @return boolean
     */
    public function getNovedad()
    {
        return $this->novedad;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TurTurno
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
     * Add turnosDetallesTurnoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurTurnoDetalle $turnosDetallesTurnoRel
     *
     * @return TurTurno
     */
    public function addTurnosDetallesTurnoRel(\Brasa\TurnoBundle\Entity\TurTurnoDetalle $turnosDetallesTurnoRel)
    {
        $this->turnosDetallesTurnoRel[] = $turnosDetallesTurnoRel;

        return $this;
    }

    /**
     * Remove turnosDetallesTurnoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurTurnoDetalle $turnosDetallesTurnoRel
     */
    public function removeTurnosDetallesTurnoRel(\Brasa\TurnoBundle\Entity\TurTurnoDetalle $turnosDetallesTurnoRel)
    {
        $this->turnosDetallesTurnoRel->removeElement($turnosDetallesTurnoRel);
    }

    /**
     * Get turnosDetallesTurnoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTurnosDetallesTurnoRel()
    {
        return $this->turnosDetallesTurnoRel;
    }

    /**
     * Add pedidosDetallesTurnoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesTurnoRel
     *
     * @return TurTurno
     */
    public function addPedidosDetallesTurnoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesTurnoRel)
    {
        $this->pedidosDetallesTurnoRel[] = $pedidosDetallesTurnoRel;

        return $this;
    }

    /**
     * Remove pedidosDetallesTurnoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesTurnoRel
     */
    public function removePedidosDetallesTurnoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesTurnoRel)
    {
        $this->pedidosDetallesTurnoRel->removeElement($pedidosDetallesTurnoRel);
    }

    /**
     * Get pedidosDetallesTurnoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDetallesTurnoRel()
    {
        return $this->pedidosDetallesTurnoRel;
    }

    /**
     * Add cotizacionesDetallesTurnoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesTurnoRel
     *
     * @return TurTurno
     */
    public function addCotizacionesDetallesTurnoRel(\Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesTurnoRel)
    {
        $this->cotizacionesDetallesTurnoRel[] = $cotizacionesDetallesTurnoRel;

        return $this;
    }

    /**
     * Remove cotizacionesDetallesTurnoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesTurnoRel
     */
    public function removeCotizacionesDetallesTurnoRel(\Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesTurnoRel)
    {
        $this->cotizacionesDetallesTurnoRel->removeElement($cotizacionesDetallesTurnoRel);
    }

    /**
     * Get cotizacionesDetallesTurnoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCotizacionesDetallesTurnoRel()
    {
        return $this->cotizacionesDetallesTurnoRel;
    }

    /**
     * Add soportesPagosDetallesTurnoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesTurnoRel
     *
     * @return TurTurno
     */
    public function addSoportesPagosDetallesTurnoRel(\Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesTurnoRel)
    {
        $this->soportesPagosDetallesTurnoRel[] = $soportesPagosDetallesTurnoRel;

        return $this;
    }

    /**
     * Remove soportesPagosDetallesTurnoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesTurnoRel
     */
    public function removeSoportesPagosDetallesTurnoRel(\Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesTurnoRel)
    {
        $this->soportesPagosDetallesTurnoRel->removeElement($soportesPagosDetallesTurnoRel);
    }

    /**
     * Get soportesPagosDetallesTurnoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSoportesPagosDetallesTurnoRel()
    {
        return $this->soportesPagosDetallesTurnoRel;
    }
}