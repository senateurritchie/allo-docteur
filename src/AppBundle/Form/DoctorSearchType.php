<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use AppBundle\Entity\Doctor;
use AppBundle\Entity\Job;
use AppBundle\Entity\DoctorType;
use AppBundle\Entity\Specialization;
use AppBundle\Entity\City;

use AppBundle\Form\UserSearchType;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class DoctorSearchType extends AbstractType
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
            "choice_label"=>function($el,$key,$index){
                return ucwords($el->getName());
            },
            "choice_value"=>"slug",
            'group_by' => function($el, $key, $index) {
                return strtoupper($el->getSlug()[0]);
            },
            "required"=>false,
            "mapped"=>false,
        ))
        ->add('city',EntityType::class,array(
            "class"=>City::class,
            "attr"=>array(
                "class"=>"form-control-sm custom-select"
            ),
            "choice_label"=>function($el,$key,$index){
                return ucwords($el->getName());
            },
            "choice_value"=>"slug",
            'group_by' => function($el, $key, $index) {
                return strtoupper($el->getSlug()[0]);
            },
            "required"=>false,
            "mapped"=>false,
        ))
        ->add('doctorType',EntityType::class,array(
            "class"=>DoctorType::class,
            "attr"=>array(
                "class"=>"form-control-sm custom-select"
            ),
            "required"=>false,
            "choice_label"=>function($el,$key,$index){
                return ucwords($el->getName());
            },
            "choice_value"=>"slug",
        ))
        ->add('job',EntityType::class,array(
            "class"=>Job::class,
            "attr"=>array(
                "class"=>"form-control-sm custom-select"
            ),
            "required"=>false,
            "choice_label"=>function($el,$key,$index){
                return ucwords($el->getName());
            },
            "choice_value"=>"slug",
        ))
        ;
        
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            "use_for_mode"=>"",
            'data_class' => Doctor::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return null;
    }
}
