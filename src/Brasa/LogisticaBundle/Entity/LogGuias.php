<?php

namespace Brasa\LogisticaBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="log_guias")
 * @ORM\Entity(repositoryClass="Brasa\LogisticaBundle\Repository\LogGuiasRepository")
 */
class LogGuias
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_guia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoGuiaPk;
    
    /**
     * @ORM\Column(name="numero_guia", type="integer", nullable=true)
     */    
    private $numeroGuia;    
    
    /**
     * @ORM\Column(name="fecha_ingreso", type="datetime", nullable=true)
     */    
    private $fechaIngreso;        

    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */    
    private $codigoTerceroFk;    
    
    /**
     * @ORM\Column(name="codigo_despacho_fk", type="integer", nullable=true)
     */    
    private $codigoDespachoFk;    
    
    /**
     * @ORM\Column(name="documento_cliente", type="string", length=60, nullable=true)
     */    
    private $documentoCliente;    

    /**
     * @ORM\Column(name="nombre_destinatario", type="string", length=80, nullable=true)
     */    
    private $nombreDestinatario;
    
    /**
     * @ORM\Column(name="direccion_destinatario", type="string", length=80, nullable=true)
     */    
    private $direccionDestinatario;    
    
    /**
     * @ORM\Column(name="telefono_destinatario", type="string", length=15, nullable=true)
     */    
    private $telefonoDestinatario;        
    
    /**
     * @ORM\Column(name="codigo_ciudad_origen_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadOrigenFk;     

    /**
     * @ORM\Column(name="codigo_ciudad_destino_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadDestinoFk;    
    
    /**
     * @ORM\Column(name="codigo_ruta_fk", type="integer", nullable=true)
     */    
    private $codigoRutaFk;     
    
    /**
     * @ORM\Column(name="codigo_tipo_servicio_fk", type="integer", nullable=true)
     */    
    private $codigoTipoServicioFk;    
    
    /**
     * @ORM\Column(name="codigo_tipo_pago_fk", type="integer", nullable=true)
     */    
    private $codigoTipoPagoFk;     
    
    /**
     * @ORM\Column(name="codigo_punto_operacion_ingreso_fk", type="integer", nullable=true)
     */    
    private $codigoPuntoOperacionIngresoFk;     

    /**
     * @ORM\Column(name="codigo_punto_operacion_actual_fk", type="integer", nullable=true)
     */    
    private $codigoPuntoOperacionActualFk;     
   
    
    /**
     * @ORM\Column(name="ct_unidades", type="integer")
     */
    private $ctUnidades = 0;

    /**
     * @ORM\Column(name="ct_peso_real", type="integer")
     */
    private $ctPesoReal = 0;    

    /**
     * @ORM\Column(name="ct_peso_volumen", type="integer")
     */
    private $ctPesoVolumen = 0;    
    
    /**
     * @ORM\Column(name="ct_peso_facturar", type="integer")
     */
    private $ctPesoFacturar = 0;    

    /**
     * @ORM\Column(name="vr_declarado", type="float")
     */
    private $vrDeclarado = 0;    
    
    /**
     * @ORM\Column(name="vr_flete", type="float")
     */
    private $vrFlete = 0;
    
    /**
     * @ORM\Column(name="vr_manejo", type="float")
     */
    private $vrManejo = 0;

    /**
     * @ORM\Column(name="vr_recaudo", type="float")
     */
    private $vrRecaudo = 0;    
    
    /**
     * @ORM\Column(name="vr_abonos", type="float")
     */
    private $vrAbonos = 0;    
    
    /**
     * @ORM\Column(name="vr_neto", type="float")
     */
    private $vrNeto = 0;    
    
    /**
     * @ORM\Column(name="estado_impreso", type="boolean")
     */    
    private $estadoImpreso = 0;             

    /**
     * @ORM\Column(name="estado_anulada", type="boolean")
     */    
    private $estadoAnulada = 0;    

    /**
     * @ORM\Column(name="estado_despachada", type="boolean")
     */    
    private $estadoDespachada = 0;    
    
    /**
     * @ORM\Column(name="estado_entregada", type="boolean")
     */    
    private $estadoEntregada = 0;    
    
    /**
     * @ORM\Column(name="estado_descargada", type="boolean")
     */    
    private $estadoDescargada = 0;    
    
    /**
     * @ORM\Column(name="fecha_entrega", type="datetime", nullable=true)
     */    
    private $fechaEntrega;
    
    /**
     * @ORM\Column(name="fecha_descargada", type="datetime", nullable=true)
     */    
    private $fechaDescargada;    
    
    /**
     * @ORM\Column(name="contenido", type="string", length=500, nullable=true)
     */    
    private $contenido;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     */    
    private $comentarios;    
    
    /**
     * @ORM\ManyToOne(targetEntity="LogDespachos", inversedBy="guiasDetallesRel")
     * @ORM\JoinColumn(name="codigo_despacho_fk", referencedColumnName="codigo_despacho_pk")
     */
    protected $despachoRel;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudades", inversedBy="guiasCiudadOrigenRel")
     * @ORM\JoinColumn(name="codigo_ciudad_origen_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadOrigenRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudades", inversedBy="guiasCiudadDestinoRel")
     * @ORM\JoinColumn(name="codigo_ciudad_destino_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadDestinoRel;     
        
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenTerceros", inversedBy="guiasRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;    

    /**
     * @ORM\ManyToOne(targetEntity="LogRutas", inversedBy="guiasRel")
     * @ORM\JoinColumn(name="codigo_ruta_fk", referencedColumnName="codigo_ruta_pk")
     */
    protected $rutaRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="LogTiposServicio", inversedBy="guiasRel")
     * @ORM\JoinColumn(name="codigo_tipo_servicio_fk", referencedColumnName="codigo_tipo_servicio_pk")
     */
    protected $tipoServicioRel; 
    
    /**
     * @ORM\ManyToOne(targetEntity="LogTiposPago", inversedBy="guiasRel")
     * @ORM\JoinColumn(name="codigo_tipo_pago_fk", referencedColumnName="codigo_tipo_pago_pk")
     */
    protected $tipoPagoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="LogPuntosOperacion", inversedBy="guiasPuntoOperacionIngresoRel")
     * @ORM\JoinColumn(name="codigo_punto_operacion_ingreso_fk", referencedColumnName="codigo_punto_operacion_pk")
     */
    protected $puntoOperacionIngresoRel;     

    /**
     * @ORM\ManyToOne(targetEntity="LogPuntosOperacion", inversedBy="guiasPuntoOperacionActualRel")
     * @ORM\JoinColumn(name="codigo_punto_operacion_actual_fk", referencedColumnName="codigo_punto_operacion_pk")
     */
    protected $puntoOperacionActualRel;    
    
    /**
     * Get codigoGuiaPk
     *
     * @return integer 
     */
    public function getCodigoGuiaPk()
    {
        return $this->codigoGuiaPk;
    }

    /**
     * Set numeroGuia
     *
     * @param integer $numeroGuia
     * @return LogGuias
     */
    public function setNumeroGuia($numeroGuia)
    {
        $this->numeroGuia = $numeroGuia;

        return $this;
    }

    /**
     * Get numeroGuia
     *
     * @return integer 
     */
    public function getNumeroGuia()
    {
        return $this->numeroGuia;
    }

    /**
     * Set fechaIngreso
     *
     * @param \DateTime $fechaIngreso
     * @return LogGuias
     */
    public function setFechaIngreso($fechaIngreso)
    {
        $this->fechaIngreso = $fechaIngreso;

        return $this;
    }

    /**
     * Get fechaIngreso
     *
     * @return \DateTime 
     */
    public function getFechaIngreso()
    {
        return $this->fechaIngreso;
    }

    /**
     * Set codigoDespachoFk
     *
     * @param integer $codigoDespachoFk
     * @return LogGuias
     */
    public function setCodigoDespachoFk($codigoDespachoFk)
    {
        $this->codigoDespachoFk = $codigoDespachoFk;

        return $this;
    }

    /**
     * Get codigoDespachoFk
     *
     * @return integer 
     */
    public function getCodigoDespachoFk()
    {
        return $this->codigoDespachoFk;
    }

    /**
     * Set documentoCliente
     *
     * @param string $documentoCliente
     * @return LogGuias
     */
    public function setDocumentoCliente($documentoCliente)
    {
        $this->documentoCliente = $documentoCliente;

        return $this;
    }

    /**
     * Get documentoCliente
     *
     * @return string 
     */
    public function getDocumentoCliente()
    {
        return $this->documentoCliente;
    }

    /**
     * Set nombreDestinatario
     *
     * @param string $nombreDestinatario
     * @return LogGuias
     */
    public function setNombreDestinatario($nombreDestinatario)
    {
        $this->nombreDestinatario = $nombreDestinatario;

        return $this;
    }

    /**
     * Get nombreDestinatario
     *
     * @return string 
     */
    public function getNombreDestinatario()
    {
        return $this->nombreDestinatario;
    }

    /**
     * Set direccionDestinatario
     *
     * @param string $direccionDestinatario
     * @return LogGuias
     */
    public function setDireccionDestinatario($direccionDestinatario)
    {
        $this->direccionDestinatario = $direccionDestinatario;

        return $this;
    }

    /**
     * Get direccionDestinatario
     *
     * @return string 
     */
    public function getDireccionDestinatario()
    {
        return $this->direccionDestinatario;
    }

    /**
     * Set telefonoDestinatario
     *
     * @param string $telefonoDestinatario
     * @return LogGuias
     */
    public function setTelefonoDestinatario($telefonoDestinatario)
    {
        $this->telefonoDestinatario = $telefonoDestinatario;

        return $this;
    }

    /**
     * Get telefonoDestinatario
     *
     * @return string 
     */
    public function getTelefonoDestinatario()
    {
        return $this->telefonoDestinatario;
    }

    /**
     * Set codigoCiudadOrigenFk
     *
     * @param integer $codigoCiudadOrigenFk
     * @return LogGuias
     */
    public function setCodigoCiudadOrigenFk($codigoCiudadOrigenFk)
    {
        $this->codigoCiudadOrigenFk = $codigoCiudadOrigenFk;

        return $this;
    }

    /**
     * Get codigoCiudadOrigenFk
     *
     * @return integer 
     */
    public function getCodigoCiudadOrigenFk()
    {
        return $this->codigoCiudadOrigenFk;
    }

    /**
     * Set codigoCiudadDestinoFk
     *
     * @param integer $codigoCiudadDestinoFk
     * @return LogGuias
     */
    public function setCodigoCiudadDestinoFk($codigoCiudadDestinoFk)
    {
        $this->codigoCiudadDestinoFk = $codigoCiudadDestinoFk;

        return $this;
    }

    /**
     * Get codigoCiudadDestinoFk
     *
     * @return integer 
     */
    public function getCodigoCiudadDestinoFk()
    {
        return $this->codigoCiudadDestinoFk;
    }

    /**
     * Set ctUnidades
     *
     * @param integer $ctUnidades
     * @return LogGuias
     */
    public function setCtUnidades($ctUnidades)
    {
        $this->ctUnidades = $ctUnidades;

        return $this;
    }

    /**
     * Get ctUnidades
     *
     * @return integer 
     */
    public function getCtUnidades()
    {
        return $this->ctUnidades;
    }

    /**
     * Set ctPesoReal
     *
     * @param integer $ctPesoReal
     * @return LogGuias
     */
    public function setCtPesoReal($ctPesoReal)
    {
        $this->ctPesoReal = $ctPesoReal;

        return $this;
    }

    /**
     * Get ctPesoReal
     *
     * @return integer 
     */
    public function getCtPesoReal()
    {
        return $this->ctPesoReal;
    }

    /**
     * Set ctPesoVolumen
     *
     * @param integer $ctPesoVolumen
     * @return LogGuias
     */
    public function setCtPesoVolumen($ctPesoVolumen)
    {
        $this->ctPesoVolumen = $ctPesoVolumen;

        return $this;
    }

    /**
     * Get ctPesoVolumen
     *
     * @return integer 
     */
    public function getCtPesoVolumen()
    {
        return $this->ctPesoVolumen;
    }

    /**
     * Set ctPesoFacturar
     *
     * @param integer $ctPesoFacturar
     * @return LogGuias
     */
    public function setCtPesoFacturar($ctPesoFacturar)
    {
        $this->ctPesoFacturar = $ctPesoFacturar;

        return $this;
    }

    /**
     * Get ctPesoFacturar
     *
     * @return integer 
     */
    public function getCtPesoFacturar()
    {
        return $this->ctPesoFacturar;
    }

    /**
     * Set vrDeclarado
     *
     * @param float $vrDeclarado
     * @return LogGuias
     */
    public function setVrDeclarado($vrDeclarado)
    {
        $this->vrDeclarado = $vrDeclarado;

        return $this;
    }

    /**
     * Get vrDeclarado
     *
     * @return float 
     */
    public function getVrDeclarado()
    {
        return $this->vrDeclarado;
    }

    /**
     * Set vrFlete
     *
     * @param float $vrFlete
     * @return LogGuias
     */
    public function setVrFlete($vrFlete)
    {
        $this->vrFlete = $vrFlete;

        return $this;
    }

    /**
     * Get vrFlete
     *
     * @return float 
     */
    public function getVrFlete()
    {
        return $this->vrFlete;
    }

    /**
     * Set vrManejo
     *
     * @param float $vrManejo
     * @return LogGuias
     */
    public function setVrManejo($vrManejo)
    {
        $this->vrManejo = $vrManejo;

        return $this;
    }

    /**
     * Get vrManejo
     *
     * @return float 
     */
    public function getVrManejo()
    {
        return $this->vrManejo;
    }

    /**
     * Set estadoAnulada
     *
     * @param boolean $estadoAnulada
     * @return LogGuias
     */
    public function setEstadoAnulada($estadoAnulada)
    {
        $this->estadoAnulada = $estadoAnulada;

        return $this;
    }

    /**
     * Get estadoAnulada
     *
     * @return boolean 
     */
    public function getEstadoAnulada()
    {
        return $this->estadoAnulada;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     * @return LogGuias
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
     * Set despachoRel
     *
     * @param \Brasa\LogisticaBundle\Entity\LogDespachos $despachoRel
     * @return LogGuias
     */
    public function setDespachoRel(\Brasa\LogisticaBundle\Entity\LogDespachos $despachoRel = null)
    {
        $this->despachoRel = $despachoRel;

        return $this;
    }

    /**
     * Get despachoRel
     *
     * @return \Brasa\LogisticaBundle\Entity\LogDespachos 
     */
    public function getDespachoRel()
    {
        return $this->despachoRel;
    }

    /**
     * Set ciudadDestinoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudades $ciudadDestinoRel
     * @return LogGuias
     */
    public function setCiudadDestinoRel(\Brasa\GeneralBundle\Entity\GenCiudades $ciudadDestinoRel = null)
    {
        $this->ciudadDestinoRel = $ciudadDestinoRel;

        return $this;
    }

    /**
     * Get ciudadDestinoRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudades 
     */
    public function getCiudadDestinoRel()
    {
        return $this->ciudadDestinoRel;
    }

    /**
     * Set codigoTerceroFk
     *
     * @param integer $codigoTerceroFk
     * @return LogGuias
     */
    public function setCodigoTerceroFk($codigoTerceroFk)
    {
        $this->codigoTerceroFk = $codigoTerceroFk;

        return $this;
    }

    /**
     * Get codigoTerceroFk
     *
     * @return integer 
     */
    public function getCodigoTerceroFk()
    {
        return $this->codigoTerceroFk;
    }

    /**
     * Set terceroRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTerceros $terceroRel
     * @return LogGuias
     */
    public function setTerceroRel(\Brasa\GeneralBundle\Entity\GenTerceros $terceroRel = null)
    {
        $this->terceroRel = $terceroRel;

        return $this;
    }

    /**
     * Get terceroRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenTerceros 
     */
    public function getTerceroRel()
    {
        return $this->terceroRel;
    }

    /**
     * Set codigoRutaFk
     *
     * @param integer $codigoRutaFk
     * @return LogGuias
     */
    public function setCodigoRutaFk($codigoRutaFk)
    {
        $this->codigoRutaFk = $codigoRutaFk;

        return $this;
    }

    /**
     * Get codigoRutaFk
     *
     * @return integer 
     */
    public function getCodigoRutaFk()
    {
        return $this->codigoRutaFk;
    }

    /**
     * Set rutaRel
     *
     * @param \Brasa\LogisticaBundle\Entity\LogRutas $rutaRel
     * @return LogGuias
     */
    public function setRutaRel(\Brasa\LogisticaBundle\Entity\LogRutas $rutaRel = null)
    {
        $this->rutaRel = $rutaRel;

        return $this;
    }

    /**
     * Get rutaRel
     *
     * @return \Brasa\LogisticaBundle\Entity\LogRutas 
     */
    public function getRutaRel()
    {
        return $this->rutaRel;
    }

    /**
     * Set contenido
     *
     * @param string $contenido
     * @return LogGuias
     */
    public function setContenido($contenido)
    {
        $this->contenido = $contenido;

        return $this;
    }

    /**
     * Get contenido
     *
     * @return string 
     */
    public function getContenido()
    {
        return $this->contenido;
    }

    /**
     * Set vrRecaudo
     *
     * @param float $vrRecaudo
     * @return LogGuias
     */
    public function setVrRecaudo($vrRecaudo)
    {
        $this->vrRecaudo = $vrRecaudo;

        return $this;
    }

    /**
     * Get vrRecaudo
     *
     * @return float 
     */
    public function getVrRecaudo()
    {
        return $this->vrRecaudo;
    }

    /**
     * Set codigoTipoServicioFk
     *
     * @param integer $codigoTipoServicioFk
     * @return LogGuias
     */
    public function setCodigoTipoServicioFk($codigoTipoServicioFk)
    {
        $this->codigoTipoServicioFk = $codigoTipoServicioFk;

        return $this;
    }

    /**
     * Get codigoTipoServicioFk
     *
     * @return integer 
     */
    public function getCodigoTipoServicioFk()
    {
        return $this->codigoTipoServicioFk;
    }

    /**
     * Set tipoServicioRel
     *
     * @param \Brasa\LogisticaBundle\Entity\LogTiposServicio $tipoServicioRel
     * @return LogGuias
     */
    public function setTipoServicioRel(\Brasa\LogisticaBundle\Entity\LogTiposServicio $tipoServicioRel = null)
    {
        $this->tipoServicioRel = $tipoServicioRel;

        return $this;
    }

    /**
     * Get tipoServicioRel
     *
     * @return \Brasa\LogisticaBundle\Entity\LogTiposServicio 
     */
    public function getTipoServicioRel()
    {
        return $this->tipoServicioRel;
    }

    /**
     * Set ciudadOrigenRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudades $ciudadOrigenRel
     * @return LogGuias
     */
    public function setCiudadOrigenRel(\Brasa\GeneralBundle\Entity\GenCiudades $ciudadOrigenRel = null)
    {
        $this->ciudadOrigenRel = $ciudadOrigenRel;

        return $this;
    }

    /**
     * Get ciudadOrigenRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudades 
     */
    public function getCiudadOrigenRel()
    {
        return $this->ciudadOrigenRel;
    }

    /**
     * Set estadoImpreso
     *
     * @param boolean $estadoImpreso
     * @return LogGuias
     */
    public function setEstadoImpreso($estadoImpreso)
    {
        $this->estadoImpreso = $estadoImpreso;

        return $this;
    }

    /**
     * Get estadoImpreso
     *
     * @return boolean 
     */
    public function getEstadoImpreso()
    {
        return $this->estadoImpreso;
    }

    /**
     * Set codigoPuntoOperacionIngresoFk
     *
     * @param integer $codigoPuntoOperacionIngresoFk
     * @return LogGuias
     */
    public function setCodigoPuntoOperacionIngresoFk($codigoPuntoOperacionIngresoFk)
    {
        $this->codigoPuntoOperacionIngresoFk = $codigoPuntoOperacionIngresoFk;

        return $this;
    }

    /**
     * Get codigoPuntoOperacionIngresoFk
     *
     * @return integer 
     */
    public function getCodigoPuntoOperacionIngresoFk()
    {
        return $this->codigoPuntoOperacionIngresoFk;
    }

    /**
     * Set codigoPuntoOperacionActualFk
     *
     * @param integer $codigoPuntoOperacionActualFk
     * @return LogGuias
     */
    public function setCodigoPuntoOperacionActualFk($codigoPuntoOperacionActualFk)
    {
        $this->codigoPuntoOperacionActualFk = $codigoPuntoOperacionActualFk;

        return $this;
    }

    /**
     * Get codigoPuntoOperacionActualFk
     *
     * @return integer 
     */
    public function getCodigoPuntoOperacionActualFk()
    {
        return $this->codigoPuntoOperacionActualFk;
    }

    /**
     * Set puntoOperacionIngresoRel
     *
     * @param \Brasa\LogisticaBundle\Entity\LogPuntosOperacion $puntoOperacionIngresoRel
     * @return LogGuias
     */
    public function setPuntoOperacionIngresoRel(\Brasa\LogisticaBundle\Entity\LogPuntosOperacion $puntoOperacionIngresoRel = null)
    {
        $this->puntoOperacionIngresoRel = $puntoOperacionIngresoRel;

        return $this;
    }

    /**
     * Get puntoOperacionIngresoRel
     *
     * @return \Brasa\LogisticaBundle\Entity\LogPuntosOperacion 
     */
    public function getPuntoOperacionIngresoRel()
    {
        return $this->puntoOperacionIngresoRel;
    }

    /**
     * Set puntoOperacionActualRel
     *
     * @param \Brasa\LogisticaBundle\Entity\LogPuntosOperacion $puntoOperacionActualRel
     * @return LogGuias
     */
    public function setPuntoOperacionActualRel(\Brasa\LogisticaBundle\Entity\LogPuntosOperacion $puntoOperacionActualRel = null)
    {
        $this->puntoOperacionActualRel = $puntoOperacionActualRel;

        return $this;
    }

    /**
     * Get puntoOperacionActualRel
     *
     * @return \Brasa\LogisticaBundle\Entity\LogPuntosOperacion 
     */
    public function getPuntoOperacionActualRel()
    {
        return $this->puntoOperacionActualRel;
    }

    /**
     * Set vrAbonos
     *
     * @param float $vrAbonos
     * @return LogGuias
     */
    public function setVrAbonos($vrAbonos)
    {
        $this->vrAbonos = $vrAbonos;

        return $this;
    }

    /**
     * Get vrAbonos
     *
     * @return float 
     */
    public function getVrAbonos()
    {
        return $this->vrAbonos;
    }

    /**
     * Set vrNeto
     *
     * @param float $vrNeto
     * @return LogGuias
     */
    public function setVrNeto($vrNeto)
    {
        $this->vrNeto = $vrNeto;

        return $this;
    }

    /**
     * Get vrNeto
     *
     * @return float 
     */
    public function getVrNeto()
    {
        return $this->vrNeto;
    }

    /**
     * Set codigoTipoPagoFk
     *
     * @param integer $codigoTipoPagoFk
     * @return LogGuias
     */
    public function setCodigoTipoPagoFk($codigoTipoPagoFk)
    {
        $this->codigoTipoPagoFk = $codigoTipoPagoFk;

        return $this;
    }

    /**
     * Get codigoTipoPagoFk
     *
     * @return integer 
     */
    public function getCodigoTipoPagoFk()
    {
        return $this->codigoTipoPagoFk;
    }

    /**
     * Set tipoPagoRel
     *
     * @param \Brasa\LogisticaBundle\Entity\LogTiposPago $tipoPagoRel
     * @return LogGuias
     */
    public function setTipoPagoRel(\Brasa\LogisticaBundle\Entity\LogTiposPago $tipoPagoRel = null)
    {
        $this->tipoPagoRel = $tipoPagoRel;

        return $this;
    }

    /**
     * Get tipoPagoRel
     *
     * @return \Brasa\LogisticaBundle\Entity\LogTiposPago 
     */
    public function getTipoPagoRel()
    {
        return $this->tipoPagoRel;
    }

    /**
     * Set estadoEntregada
     *
     * @param boolean $estadoEntregada
     * @return LogGuias
     */
    public function setEstadoEntregada($estadoEntregada)
    {
        $this->estadoEntregada = $estadoEntregada;

        return $this;
    }

    /**
     * Get estadoEntregada
     *
     * @return boolean 
     */
    public function getEstadoEntregada()
    {
        return $this->estadoEntregada;
    }

    /**
     * Set estadoDescargada
     *
     * @param boolean $estadoDescargada
     * @return LogGuias
     */
    public function setEstadoDescargada($estadoDescargada)
    {
        $this->estadoDescargada = $estadoDescargada;

        return $this;
    }

    /**
     * Get estadoDescargada
     *
     * @return boolean 
     */
    public function getEstadoDescargada()
    {
        return $this->estadoDescargada;
    }

    /**
     * Set fechaEntrega
     *
     * @param \DateTime $fechaEntrega
     * @return LogGuias
     */
    public function setFechaEntrega($fechaEntrega)
    {
        $this->fechaEntrega = $fechaEntrega;

        return $this;
    }

    /**
     * Get fechaEntrega
     *
     * @return \DateTime 
     */
    public function getFechaEntrega()
    {
        return $this->fechaEntrega;
    }

    /**
     * Set fechaDescargada
     *
     * @param \DateTime $fechaDescargada
     * @return LogGuias
     */
    public function setFechaDescargada($fechaDescargada)
    {
        $this->fechaDescargada = $fechaDescargada;

        return $this;
    }

    /**
     * Get fechaDescargada
     *
     * @return \DateTime 
     */
    public function getFechaDescargada()
    {
        return $this->fechaDescargada;
    }

    /**
     * Set estadoDespachada
     *
     * @param boolean $estadoDespachada
     * @return LogGuias
     */
    public function setEstadoDespachada($estadoDespachada)
    {
        $this->estadoDespachada = $estadoDespachada;

        return $this;
    }

    /**
     * Get estadoDespachada
     *
     * @return boolean 
     */
    public function getEstadoDespachada()
    {
        return $this->estadoDespachada;
    }
}
