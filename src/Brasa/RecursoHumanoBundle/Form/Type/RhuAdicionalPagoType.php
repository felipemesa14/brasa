<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuAdicionalPagoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder

            ->add('pagoConceptoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
                'property' => 'nombre',
            ))
            ->add('detalle', 'text', array('required' => true))    
            ->add('empleadoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleado',
                'property' => 'nombreCorto',
            ))    
            ->add('cantidad', 'text', array('required' => true))    
            ->add('valor', 'text', array('required' => true))        
            ->add('aplicaDiaLaborado', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('guardar', 'submit');            
    }

    public function getName()
    {
        return 'form';
    }
}

