<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
class TurSoportePagoPeriodoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder       
            ->add('fechaDesde', 'date', array('format' => 'yyyyMMMMdd'))
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMMMdd'))            
            ->add('guardar', 'submit');
    }

    public function getName()
    {
        return 'form';
    }
}

