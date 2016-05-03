<?php
namespace Brasa\AfiliacionBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class AfiFacturaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder    
            ->add('facturaTipoRel', 'entity', array(
                'class' => 'BrasaAfiliacionBundle:AfiFacturaTipo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ft')
                    ->orderBy('ft.codigoFacturaTipoPk', 'ASC');},
                'property' => 'nombre',
                'required' => true))                
            ->add('clienteRel', 'entity', array(
                'class' => 'BrasaAfiliacionBundle:AfiCliente',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombreCorto', 'ASC');},
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
