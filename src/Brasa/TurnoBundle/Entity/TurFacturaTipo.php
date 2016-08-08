<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_factura_tipo")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurFacturaTipoRepository")
 */
class TurFacturaTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFacturaTipoPk;        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;                                
    
    /**
     * @ORM\Column(name="tipo", type="integer")
     */    
    private $tipo = 0;      

    /**
     * @ORM\Column(name="operacion", type="integer")
     */    
    private $operacion = 0;    
    
    /**
     * @ORM\Column(name="consecutivo", type="integer")
     */    
    private $consecutivo = 0; 
    
    /**
     * @ORM\Column(name="documento_cartera", type="integer", nullable=true)
     */    
    private $documentoCartera;     
    
    /**
     * @ORM\Column(name="abreviatura", type="string", length=10)
     */
    private $abreviatura;     
    
    /**
     * @ORM\Column(name="codigo_cuenta_cartera_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaCarteraFk;    
    
    /**
     * @ORM\Column(name="codigo_cuenta_retencion_fuente_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaRetencionFuenteFk;
    
    /**
     * @ORM\Column(name="codigo_cuenta_iva_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaIvaFk;    
    
    /**
     * @ORM\Column(name="codigo_cuenta_ingreso_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaIngresoFk;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurFactura", mappedBy="facturaTipoRel")
     */
    protected $facturasFacturaTipoRel; 
    
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->facturasFacturaTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoFacturaTipoPk
     *
     * @return integer
     */
    public function getCodigoFacturaTipoPk()
    {
        return $this->codigoFacturaTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurFacturaTipo
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
     * Set tipo
     *
     * @param integer $tipo
     *
     * @return TurFacturaTipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return integer
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set operacion
     *
     * @param integer $operacion
     *
     * @return TurFacturaTipo
     */
    public function setOperacion($operacion)
    {
        $this->operacion = $operacion;

        return $this;
    }

    /**
     * Get operacion
     *
     * @return integer
     */
    public function getOperacion()
    {
        return $this->operacion;
    }

    /**
     * Set consecutivo
     *
     * @param integer $consecutivo
     *
     * @return TurFacturaTipo
     */
    public function setConsecutivo($consecutivo)
    {
        $this->consecutivo = $consecutivo;

        return $this;
    }

    /**
     * Get consecutivo
     *
     * @return integer
     */
    public function getConsecutivo()
    {
        return $this->consecutivo;
    }

    /**
     * Set documentoCartera
     *
     * @param integer $documentoCartera
     *
     * @return TurFacturaTipo
     */
    public function setDocumentoCartera($documentoCartera)
    {
        $this->documentoCartera = $documentoCartera;

        return $this;
    }

    /**
     * Get documentoCartera
     *
     * @return integer
     */
    public function getDocumentoCartera()
    {
        return $this->documentoCartera;
    }

    /**
     * Set abreviatura
     *
     * @param string $abreviatura
     *
     * @return TurFacturaTipo
     */
    public function setAbreviatura($abreviatura)
    {
        $this->abreviatura = $abreviatura;

        return $this;
    }

    /**
     * Get abreviatura
     *
     * @return string
     */
    public function getAbreviatura()
    {
        return $this->abreviatura;
    }

    /**
     * Set codigoCuentaCarteraFk
     *
     * @param string $codigoCuentaCarteraFk
     *
     * @return TurFacturaTipo
     */
    public function setCodigoCuentaCarteraFk($codigoCuentaCarteraFk)
    {
        $this->codigoCuentaCarteraFk = $codigoCuentaCarteraFk;

        return $this;
    }

    /**
     * Get codigoCuentaCarteraFk
     *
     * @return string
     */
    public function getCodigoCuentaCarteraFk()
    {
        return $this->codigoCuentaCarteraFk;
    }

    /**
     * Set codigoCuentaRetencionFuenteFk
     *
     * @param string $codigoCuentaRetencionFuenteFk
     *
     * @return TurFacturaTipo
     */
    public function setCodigoCuentaRetencionFuenteFk($codigoCuentaRetencionFuenteFk)
    {
        $this->codigoCuentaRetencionFuenteFk = $codigoCuentaRetencionFuenteFk;

        return $this;
    }

    /**
     * Get codigoCuentaRetencionFuenteFk
     *
     * @return string
     */
    public function getCodigoCuentaRetencionFuenteFk()
    {
        return $this->codigoCuentaRetencionFuenteFk;
    }

    /**
     * Set codigoCuentaIvaFk
     *
     * @param string $codigoCuentaIvaFk
     *
     * @return TurFacturaTipo
     */
    public function setCodigoCuentaIvaFk($codigoCuentaIvaFk)
    {
        $this->codigoCuentaIvaFk = $codigoCuentaIvaFk;

        return $this;
    }

    /**
     * Get codigoCuentaIvaFk
     *
     * @return string
     */
    public function getCodigoCuentaIvaFk()
    {
        return $this->codigoCuentaIvaFk;
    }

    /**
     * Set codigoCuentaIngresoFk
     *
     * @param string $codigoCuentaIngresoFk
     *
     * @return TurFacturaTipo
     */
    public function setCodigoCuentaIngresoFk($codigoCuentaIngresoFk)
    {
        $this->codigoCuentaIngresoFk = $codigoCuentaIngresoFk;

        return $this;
    }

    /**
     * Get codigoCuentaIngresoFk
     *
     * @return string
     */
    public function getCodigoCuentaIngresoFk()
    {
        return $this->codigoCuentaIngresoFk;
    }

    /**
     * Add facturasFacturaTipoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFactura $facturasFacturaTipoRel
     *
     * @return TurFacturaTipo
     */
    public function addFacturasFacturaTipoRel(\Brasa\TurnoBundle\Entity\TurFactura $facturasFacturaTipoRel)
    {
        $this->facturasFacturaTipoRel[] = $facturasFacturaTipoRel;

        return $this;
    }

    /**
     * Remove facturasFacturaTipoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFactura $facturasFacturaTipoRel
     */
    public function removeFacturasFacturaTipoRel(\Brasa\TurnoBundle\Entity\TurFactura $facturasFacturaTipoRel)
    {
        $this->facturasFacturaTipoRel->removeElement($facturasFacturaTipoRel);
    }

    /**
     * Get facturasFacturaTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasFacturaTipoRel()
    {
        return $this->facturasFacturaTipoRel;
    }
}
