<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TurFacturaDetalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {       
        $builder
            ->add('puestoRel', EntityType::class, array(
                'class' => 'BrasaTurnoBundle:TurPuesto',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('p')
                    ->where('p.codigoClienteFk = :codigoCliente ')
                    ->setParameter('codigoCliente', $options['data']->getFacturaRel()->getCodigoClienteFk())
                    ->orderBy('p.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))  
            ->add('grupoFacturacionRel', EntityType::class, array(
                'class' => 'BrasaTurnoBundle:TurGrupoFacturacion',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('gf')
                    ->where('gf.codigoClienteFk = :codigoCliente ')
                    ->setParameter('codigoCliente', $options['data']->getFacturaRel()->getCodigoClienteFk())
                    ->orderBy('gf.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => false))                             
            ->add('conceptoServicioRel', EntityType::class, array(
                'class' => 'BrasaTurnoBundle:TurConceptoServicio',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('cs')
                    ->orderBy('cs.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))              
            ->add('modalidadServicioRel', EntityType::class, array(
                'class' => 'BrasaTurnoBundle:TurModalidadServicio',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ms')
                    ->orderBy('ms.nombre', 'DESC');},
                'choice_label' => 'nombre',
                'required' => true))                                                         
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

