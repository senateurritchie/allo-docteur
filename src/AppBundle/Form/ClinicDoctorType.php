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



use AppBundle\Entity\ClinicDoctor;
use AppBundle\Entity\Clinic;

class ClinicDoctorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('clinic',EntityType::class,array(
            "class"=>Clinic::class,
            "attr"=>array(
                "class"=>"form-control-sm custom-select"
            ),
            "choice_label"=>function($el,$key,$index){
                return ucwords($el->getUser()->getUsername());
            },
            'group_by' => function($el, $key, $index) {
                return strtoupper($el->getUser()->getSlug()[0]);
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
            'data_class' => ClinicDoctor::class,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(){
        return null;
    }
}
