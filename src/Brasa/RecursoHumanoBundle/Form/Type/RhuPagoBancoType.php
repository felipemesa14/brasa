<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuPagoBancoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder           
            ->add('cuentaRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenCuenta',
                'property' => 'nombre',
            ))
            ->add('descripcion', 'text', array('data'  => ''),array('required' => true))
            ->add('fechaTrasmision', 'date', array('data' => new \DateTime('now')))
            ->add('secuencia', 'choice', array('choices' => array('A' => 'A', 'B' => 'B','C' => 'C', 'D' => 'D','E' => 'E', 'F' => 'F','G' => 'G', 'H' => 'H','I' => 'I', 'J' => 'J','K' => 'K', 'L' => 'L','M' => 'M', 'N' => 'N','O' => 'O', 'P' => 'P','Q' => 'Q', 'R' => 'R','S' => 'S', 'T' => 'T', 'U' => 'U', 'V' => 'V', 'W' => 'W', 'X' => 'X', 'Y' => 'Y', 'Z' => 'Z'),))
            ->add('fechaAplicacion', 'date', array('data' => new \DateTime('now')))    
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getName()
    {
        return 'form';
    }
}

