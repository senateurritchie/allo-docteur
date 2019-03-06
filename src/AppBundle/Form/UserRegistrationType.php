<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\RequestStack;


use AppBundle\Entity\User;
use AppBundle\Entity\City;

use AppBundle\Form\DoctorSpecializationType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class UserRegistrationType extends AbstractType
{
    protected $requestStack;

    public function __construct(RequestStack $requestStack){
        $this->requestStack = $requestStack;
    }

    /**
    * {@inheritdoc}
    */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $request = $this->requestStack->getCurrentRequest();

        $builder
        ->add('firstname',TextType::class)
        ->add('lastname',TextType::class)
        ->add('email',EmailType::class)
        ->add('phone',TextType::class)
        ->add('city',EntityType::class,array(
            "class"=>City::class,
            "attr"=>array(
                "class"=>"form-control-sm custom-select"
            ),
            "required"=>false,
            "choice_label"=>function($el,$key,$index){
                return ucwords($el->getName());
            },
            'group_by' => function($el, $key, $index) {
                return strtoupper($el->getSlug()[0]);
            },
            "choice_value"=>"slug",
        ))
       /* ->add('password',RepeatedType::class,[
            'type' => PasswordType::class,
        ])*/
        ->add('profileLinkedin',UrlType::class)
        ->add('profileFacebook',UrlType::class)
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
        ->add('image',FileType::class,array(
            "required"=>false,
            "label"=>"Photo",
            "attr"=>["accept"=>"image/png, image/jpeg, image/jpg","class"=>"hide"]
        ))
        ;

        switch ($request->attributes->get('type')) {
            case 'doctor':
                $builder->remove('profileFacebook');
            break;

            default:
                $builder->remove('lastname');
            break;
        }
    }

    /**
    * {@inheritdoc}
    */
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setRequired('upload_dir');

        $resolver->setDefaults(array(
            'usr_roles'=>array(),
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
