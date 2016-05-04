<?php
namespace Brasa\AfiliacionBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class AfiPagoCursoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder    
            ->add('entidadEntrenamientoRel', 'entity', array(
                'class' => 'BrasaAfiliacionBundle:AfiEntidadEntrenamiento',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ee')
                    ->orderBy('ee.codigoEntidadEntrenamientoPk', 'ASC');},
                'property' => 'nombreCorto',
                'required' => true))                
            ->add('soporte', 'text', array('required' => false))
            ->add('comentarios', 'textarea', array('required' => false))                            
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

