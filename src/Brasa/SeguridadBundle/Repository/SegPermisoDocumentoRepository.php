<?php

namespace Brasa\SeguridadBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SegPermisoDocumentoRepository extends EntityRepository {
    
    public function permiso($arUsuario, $codigoDocumento, $tipo) {        
        $em = $this->getEntityManager();
        $boolPermiso = false;
        $arPermisoDocumento = new \Brasa\SeguridadBundle\Entity\SegPermisoDocumento();
        $arPermisoDocumento = $em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->findOneBy(array('codigoUsuarioFk' => $arUsuario->getId(), 'codigoDocumentoFk' => $codigoDocumento));
        if(count($arPermisoDocumento) > 0) {
            switch ($tipo) {
                case 1: //Ingreso
                    if($arPermisoDocumento->getIngreso() == 1) {
                        $boolPermiso = true;
                    }
                    break;
                case 4: //Eliminar
                    if($arPermisoDocumento->getEliminar() == 1) {
                        $boolPermiso = true;
                    }
                    break;                    
            }     
        }                                           
        return $boolPermiso;        
    }    
}