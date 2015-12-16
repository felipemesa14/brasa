<?php
namespace Brasa\TransporteBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TteProductoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                                             
            ->add('nombre', 'text', array('required' => true))
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'));
    }
 
    public function getName()
    {
        return 'form';
    }
}

