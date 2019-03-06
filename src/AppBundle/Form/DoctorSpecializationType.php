<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;



use AppBundle\Entity\DoctorSpecialization;
use AppBundle\Entity\Specialization;

class DoctorSpecializationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('specialization',EntityType::class,array(
            "class"=>Specialization::class,
            "attr"=>array(
                "class"=>"form-control-sm custom-select"
            ),
            "required"=>false,
            "choice_label"=>function($el,$key,$index){
                return ucwords($el->getName());
            },
            "choice_value"=>"slug",
            'group_by' => function($el, $key, $index) {
                return strtoupper($el->getSlug()[0]);
            },
        ))
        ->add('button',ButtonType::class,array(
            "attr"=>["class"=>"btn-sm delete pull-left btn btn-danger"],
            "label"=>"supprimer"
        ))
        ->addEventListener(FormEvents::PRE_SET_DATA,function(FormEvent $event)use(&$options,&$builder){
            $model = $event->getData();
            $form = $event->getForm();

            if (!$model) {
                return;
            }   //
        });
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => DoctorSpecialization::class,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(){
        return null;
    }
}
