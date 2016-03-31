<?php

namespace Brasa\CarteraBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CuentasCobrarRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CarNotaDebitoConceptoRepository extends EntityRepository
{
    public function ListaDql($strNombre = "", $strCodigo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT ndc FROM BrasaCarteraBundle:CarNotaDebitoConcepto ndc WHERE ndc.codigoNotaDebitoConceptoPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND ndc.nombre LIKE '%" . $strNombre . "%'";
        }
        if($strCodigo != "" ) {
            $dql .= " AND ndc.codigoNotaDebitoConceptoPk LIKE '%" . $strCodigo . "%'";
        }
        $dql .= " ORDER BY ndc.nombre";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaCarteraBundle:CarNotaDebitoConcepto')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    } 
}