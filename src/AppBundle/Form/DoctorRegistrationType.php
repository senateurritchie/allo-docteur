<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use AppBundle\Entity\Doctor;
use AppBundle\Entity\Job;
use AppBundle\Entity\User;
use AppBundle\Entity\Country;
use AppBundle\Entity\DoctorType;


use AppBundle\Form\ClinicDoctorType;



use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


use AppBundle\Form\UserRegistrationType;

class DoctorRegistrationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('user',UserRegistrationType::class,[
            "upload_dir"=>$options["upload_dir"],
            "label"=> false
        ])
        
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
        ->add('clinics',CollectionType::class,array(
            'entry_type' => ClinicDoctorType::class,
            'entry_options' => array(
                
            ),
            'by_reference' => false,
            "allow_add"=>true,
            "allow_delete"=>true,
            'delete_empty'=>true,
            "label"=>"Centre(s) de santé",
            "required"=>false,
        ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver){

        $resolver->setRequired('upload_dir');

        $resolver->setDefaults(array(

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
