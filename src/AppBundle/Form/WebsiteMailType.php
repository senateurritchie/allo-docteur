<?php

namespace AppBundle\Form;

use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;



use AppBundle\Entity\WebsiteMail;

class WebsiteMailType extends AbstractType
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
        $builder
        ->add('firstname',TextType::class,array(
            "attr"=>array(
                "class"=>"form-control-sm",
            ),
            "label"=>false,
        ))
        ->add('lastname',TextType::class,array(
            "attr"=>array(
                "class"=>"form-control-sm",
            ),
            "label"=>false,
        ))
        ->add('email',EmailType::class,array(
            "attr"=>array(
                "class"=>"form-control-sm",
            ),
            "label"=>false,
        ))
        ->add('subject',TextType::class,array(
            "attr"=>array(
                "class"=>"form-control-sm",
            ),
            "label"=>false,
        ))
        ->add('message',TextareaType::class,array(
            "attr"=>array(
                "class"=>"form-control-sm",
                'style'=>'min-height:170px;resize:none',
            ),
            "label"=>false,
        ))
       ;

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => WebsiteMail::class
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
