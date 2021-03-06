<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuVacacionDisfruteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                        
            
            ->add('fechaDesde','date')
            ->add('fechaHasta','date')
            ->add('comentarios', 'textarea', array('required' => false))    
            ->add('guardar', 'submit');
    }
 
    public function getName()
    {
        return 'form';
    }
}

