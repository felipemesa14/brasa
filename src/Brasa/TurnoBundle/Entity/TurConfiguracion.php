<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_configuracion")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurConfiguracionRepository")
 */
class TurConfiguracion
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_configuracion_pk", type="integer")
     */
    private $codigoConfiguracionPk;
    
    /**
     * @ORM\Column(name="informacion_legal_factura", type="text", nullable=true)
     */    
    private $informacionLegalFactura; 

    /**
     * @ORM\Column(name="informacion_pago_factura", type="text", nullable=true)
     */    
    private $informacionPagoFactura;     
    
    /**
     * @ORM\Column(name="informacion_contacto_factura", type="text", nullable=true)
     */    
    private $informacionContactoFactura;    
    
    /**
     * Set codigoConfiguracionPk
     *
     * @param integer $codigoConfiguracionPk
     *
     * @return TurConfiguracion
     */
    public function setCodigoConfiguracionPk($codigoConfiguracionPk)
    {
        $this->codigoConfiguracionPk = $codigoConfiguracionPk;

        return $this;
    }

    /**
     * Get codigoConfiguracionPk
     *
     * @return integer
     */
    public function getCodigoConfiguracionPk()
    {
        return $this->codigoConfiguracionPk;
    }

    /**
     * Set informacionLegalFactura
     *
     * @param string $informacionLegalFactura
     *
     * @return TurConfiguracion
     */
    public function setInformacionLegalFactura($informacionLegalFactura)
    {
        $this->informacionLegalFactura = $informacionLegalFactura;

        return $this;
    }

    /**
     * Get informacionLegalFactura
     *
     * @return string
     */
    public function getInformacionLegalFactura()
    {
        return $this->informacionLegalFactura;
    }

    /**
     * Set informacionPagoFactura
     *
     * @param string $informacionPagoFactura
     *
     * @return TurConfiguracion
     */
    public function setInformacionPagoFactura($informacionPagoFactura)
    {
        $this->informacionPagoFactura = $informacionPagoFactura;

        return $this;
    }

    /**
     * Get informacionPagoFactura
     *
     * @return string
     */
    public function getInformacionPagoFactura()
    {
        return $this->informacionPagoFactura;
    }

    /**
     * Set informacionContactoFactura
     *
     * @param string $informacionContactoFactura
     *
     * @return TurConfiguracion
     */
    public function setInformacionContactoFactura($informacionContactoFactura)
    {
        $this->informacionContactoFactura = $informacionContactoFactura;

        return $this;
    }

    /**
     * Get informacionContactoFactura
     *
     * @return string
     */
    public function getInformacionContactoFactura()
    {
        return $this->informacionContactoFactura;
    }
}