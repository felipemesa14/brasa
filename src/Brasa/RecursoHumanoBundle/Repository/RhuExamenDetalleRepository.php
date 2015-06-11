<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuExamenDetalleRepository extends EntityRepository {

    public function eliminarDetallesSeleccionados($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigoExamenDetalle) {                
                $arExamenDetale = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->find($codigoExamenDetalle);
                $em->remove($arExamenDetale);  
            }                                            
        }
            $em->flush();       
    }
    
    public function aprobarDetallesSeleccionados($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigoExamenDetalle) {                
                $arExamenDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->find($codigoExamenDetalle);
                if ($arExamenDetalle->getEstadoAprobado()== 0){
                    $arExamenDetalle->setEstadoAprobado(1);
                    $em->persist($arExamenDetalle);
                }
            }                                            
        }
            $em->flush();       
    }
}
