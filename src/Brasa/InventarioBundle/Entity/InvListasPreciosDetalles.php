<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_listas_precios_detalles")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvListasPreciosDetallesRepository")
 */
class InvListasPreciosDetalles
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_lista_precios_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoListaPreciosDetallePk;

    /**
     * @ORM\Column(name="codigo_lista_precios_fk", type="integer")
     */     
    private $codigoListaPreciosFk;     
    
    /**
     * @ORM\Column(name="codigo_item_fk", type="integer", nullable=true)
     */     
    private $codigoItemFk;    
    
    /**
     * @ORM\Column(name="precio", type="float")
     */    
    private $precio = 0;      

    /**
     * @ORM\Column(name="factor", type="integer")
     */    
    private $factor = 0;
    
    /**
     * @ORM\Column(name="precio_umm", type="float")
     */    
    private $precioUMM = 0;     
    
    /**
     * @ORM\Column(name="estado_inactiva", type="boolean")
     */    
    private $estadoInactiva = 0;            
    
    /**
     * @ORM\ManyToOne(targetEntity="InvListasPrecios", inversedBy="InvListasPreciosDetalles")
     * @ORM\JoinColumn(name="codigo_lista_precios_fk", referencedColumnName="codigo_lista_precios_pk")
     */
    protected $listaPrecioRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="InvItem", inversedBy="InvListasPreciosDetalles")
     * @ORM\JoinColumn(name="codigo_item_fk", referencedColumnName="codigo_item_pk")
     */
    protected $itemRel;



    /**
     * Get codigoListaPreciosDetallePk
     *
     * @return integer 
     */
    public function getCodigoListaPreciosDetallePk()
    {
        return $this->codigoListaPreciosDetallePk;
    }

    /**
     * Set codigoListaPreciosFk
     *
     * @param integer $codigoListaPreciosFk
     * @return InvListasPreciosDetalles
     */
    public function setCodigoListaPreciosFk($codigoListaPreciosFk)
    {
        $this->codigoListaPreciosFk = $codigoListaPreciosFk;

        return $this;
    }

    /**
     * Get codigoListaPreciosFk
     *
     * @return integer 
     */
    public function getCodigoListaPreciosFk()
    {
        return $this->codigoListaPreciosFk;
    }

    /**
     * Set codigoItemFk
     *
     * @param integer $codigoItemFk
     * @return InvListasPreciosDetalles
     */
    public function setCodigoItemFk($codigoItemFk)
    {
        $this->codigoItemFk = $codigoItemFk;

        return $this;
    }

    /**
     * Get codigoItemFk
     *
     * @return integer 
     */
    public function getCodigoItemFk()
    {
        return $this->codigoItemFk;
    }

    /**
     * Set precio
     *
     * @param float $precio
     * @return InvListasPreciosDetalles
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return float 
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set factor
     *
     * @param integer $factor
     * @return InvListasPreciosDetalles
     */
    public function setFactor($factor)
    {
        $this->factor = $factor;

        return $this;
    }

    /**
     * Get factor
     *
     * @return integer 
     */
    public function getFactor()
    {
        return $this->factor;
    }

    /**
     * Set precioUMM
     *
     * @param float $precioUMM
     * @return InvListasPreciosDetalles
     */
    public function setPrecioUMM($precioUMM)
    {
        $this->precioUMM = $precioUMM;

        return $this;
    }

    /**
     * Get precioUMM
     *
     * @return float 
     */
    public function getPrecioUMM()
    {
        return $this->precioUMM;
    }

    /**
     * Set estadoInactiva
     *
     * @param boolean $estadoInactiva
     * @return InvListasPreciosDetalles
     */
    public function setEstadoInactiva($estadoInactiva)
    {
        $this->estadoInactiva = $estadoInactiva;

        return $this;
    }

    /**
     * Get estadoInactiva
     *
     * @return boolean 
     */
    public function getEstadoInactiva()
    {
        return $this->estadoInactiva;
    }

    /**
     * Set listaPrecioRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvListasPrecios $listaPrecioRel
     * @return InvListasPreciosDetalles
     */
    public function setListaPrecioRel(\Brasa\InventarioBundle\Entity\InvListasPrecios $listaPrecioRel = null)
    {
        $this->listaPrecioRel = $listaPrecioRel;

        return $this;
    }

    /**
     * Get listaPrecioRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvListasPrecios 
     */
    public function getListaPrecioRel()
    {
        return $this->listaPrecioRel;
    }

    /**
     * Set itemRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvItem $itemRel
     * @return InvListasPreciosDetalles
     */
    public function setItemRel(\Brasa\InventarioBundle\Entity\InvItem $itemRel = null)
    {
        $this->itemRel = $itemRel;

        return $this;
    }

    /**
     * Get itemRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvItem 
     */
    public function getItemRel()
    {
        return $this->itemRel;
    }
}
