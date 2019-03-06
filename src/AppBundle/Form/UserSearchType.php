<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use AppBundle\Entity\User;
use AppBundle\Entity\City;

use AppBundle\Form\DoctorSpecializationType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class UserSearchType extends AbstractType
{
    /**
    * {@inheritdoc}
    */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder  
        ->add('username',TextType::class,array(
            "required"=>false,
        ))
        ->add('specializations',CollectionType::class,array(
            'entry_type' => DoctorSpecializationType::class,
            'entry_options' => array(
               
            ),
            'by_reference' => false,
            "allow_add"=>true,
            "allow_delete"=>true,
            'delete_empty'=>true,
            "label"=>"Les Spécialités",
            "required"=>false,
        ))
        ->add('city',EntityType::class,array(
            "class"=>City::class,
            "attr"=>array(
                "class"=>"form-control-sm custom-select"
            ),
            "choice_label"=>function($el,$key,$index){
                return ucwords($el->getName());
            },
            'group_by' => function($el, $key, $index) {
                return strtoupper($el->getSlug()[0]);
            },
            "choice_value"=>"slug",
            "required"=>false,
        ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            "use_for_mode"=>"",
            'data_class' => User::class
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
