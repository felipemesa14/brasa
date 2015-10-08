<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_pago_banco")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPagoBancoRepository")
 */
class RhuPagoBanco
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_banco_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoBancoPk;         
    
    /**
     * @ORM\Column(name="fecha_trasmision", type="date", nullable=true)
     */    
    private $fechaTrasmision;    
    
    /**
     * @ORM\Column(name="fecha_aplicacion", type="date", nullable=true)
     */    
    private $fechaAplicacion;    
    
    /**
     * @ORM\Column(name="secuencia", type="string", length=1, nullable=true)
     */    
    private $secuencia;    
    
    /**
     * @ORM\Column(name="descripcion", type="string", length=50, nullable=true)
     */    
    private $descripcion;     
    
    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="integer", nullable=true)
     */    
    private $codigoCuentaFk;
    
    /**
     * @ORM\Column(name="vr_total_pago", type="float")
     */
    private $vrTotalPago = 0;     

    /**
     * @ORM\Column(name="numero_registros", type="integer")
     */
    private $numeroRegistros = 0;    
    
    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */
    private $estadoAutorizado = 0;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCuenta", inversedBy="rhuPagosBancosCuentaRel")
     * @ORM\JoinColumn(name="codigo_cuenta_fk", referencedColumnName="codigo_cuenta_pk")
     */
    protected $cuentaRel;     

    /**
     * @ORM\OneToMany(targetEntity="RhuPagoBancoDetalle", mappedBy="pagoBancoRel")
     */
    protected $pagosBancosDetallesPagoBancoRel;     
    
    /**
     * Get codigoPagoBancoPk
     *
     * @return integer
     */
    public function getCodigoPagoBancoPk()
    {
        return $this->codigoPagoBancoPk;
    }

    /**
     * Set fechaTrasmision
     *
     * @param \DateTime $fechaTrasmision
     *
     * @return RhuPagoBanco
     */
    public function setFechaTrasmision($fechaTrasmision)
    {
        $this->fechaTrasmision = $fechaTrasmision;

        return $this;
    }

    /**
     * Get fechaTrasmision
     *
     * @return \DateTime
     */
    public function getFechaTrasmision()
    {
        return $this->fechaTrasmision;
    }

    /**
     * Set fechaAplicacion
     *
     * @param \DateTime $fechaAplicacion
     *
     * @return RhuPagoBanco
     */
    public function setFechaAplicacion($fechaAplicacion)
    {
        $this->fechaAplicacion = $fechaAplicacion;

        return $this;
    }

    /**
     * Get fechaAplicacion
     *
     * @return \DateTime
     */
    public function getFechaAplicacion()
    {
        return $this->fechaAplicacion;
    }

    /**
     * Set secuencia
     *
     * @param string $secuencia
     *
     * @return RhuPagoBanco
     */
    public function setSecuencia($secuencia)
    {
        $this->secuencia = $secuencia;

        return $this;
    }

    /**
     * Get secuencia
     *
     * @return string
     */
    public function getSecuencia()
    {
        return $this->secuencia;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return RhuPagoBanco
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set codigoCuentaFk
     *
     * @param integer $codigoCuentaFk
     *
     * @return RhuPagoBanco
     */
    public function setCodigoCuentaFk($codigoCuentaFk)
    {
        $this->codigoCuentaFk = $codigoCuentaFk;

        return $this;
    }

    /**
     * Get codigoCuentaFk
     *
     * @return integer
     */
    public function getCodigoCuentaFk()
    {
        return $this->codigoCuentaFk;
    }

    /**
     * Set cuentaRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCuenta $cuentaRel
     *
     * @return RhuPagoBanco
     */
    public function setCuentaRel(\Brasa\GeneralBundle\Entity\GenCuenta $cuentaRel = null)
    {
        $this->cuentaRel = $cuentaRel;

        return $this;
    }

    /**
     * Get cuentaRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCuenta
     */
    public function getCuentaRel()
    {
        return $this->cuentaRel;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pagosBancosDetallesPagoBancoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add pagosBancosDetallesPagoBancoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle $pagosBancosDetallesPagoBancoRel
     *
     * @return RhuPagoBanco
     */
    public function addPagosBancosDetallesPagoBancoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle $pagosBancosDetallesPagoBancoRel)
    {
        $this->pagosBancosDetallesPagoBancoRel[] = $pagosBancosDetallesPagoBancoRel;

        return $this;
    }

    /**
     * Remove pagosBancosDetallesPagoBancoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle $pagosBancosDetallesPagoBancoRel
     */
    public function removePagosBancosDetallesPagoBancoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle $pagosBancosDetallesPagoBancoRel)
    {
        $this->pagosBancosDetallesPagoBancoRel->removeElement($pagosBancosDetallesPagoBancoRel);
    }

    /**
     * Get pagosBancosDetallesPagoBancoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosBancosDetallesPagoBancoRel()
    {
        return $this->pagosBancosDetallesPagoBancoRel;
    }

    /**
     * Set vrTotalPago
     *
     * @param float $vrTotalPago
     *
     * @return RhuPagoBanco
     */
    public function setVrTotalPago($vrTotalPago)
    {
        $this->vrTotalPago = $vrTotalPago;

        return $this;
    }

    /**
     * Get vrTotalPago
     *
     * @return float
     */
    public function getVrTotalPago()
    {
        return $this->vrTotalPago;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return RhuPagoBanco
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

    /**
     * Set numeroRegistros
     *
     * @param integer $numeroRegistros
     *
     * @return RhuPagoBanco
     */
    public function setNumeroRegistros($numeroRegistros)
    {
        $this->numeroRegistros = $numeroRegistros;

        return $this;
    }

    /**
     * Get numeroRegistros
     *
     * @return integer
     */
    public function getNumeroRegistros()
    {
        return $this->numeroRegistros;
    }
}