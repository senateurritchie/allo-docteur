<?php

namespace AppBundle\Form;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use AppBundle\Entity\Clinic;
use AppBundle\Entity\Specialization;
use AppBundle\Entity\City;


use AppBundle\Form\UserSearchType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ClinicSearchType extends AbstractType
{
    protected $requestStack;
    protected $em;

    public function __construct(RequestStack $requestStack, ObjectManager  $em){
        $this->requestStack = $requestStack;
        $this->em = $em;
    }

    /**
    * {@inheritdoc}
    */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $request = $this->requestStack->getCurrentRequest();

        $data_specialization = null;
        $data_city = null;

        if(($request->query->get('specialization'))){
            $rep = $this->em->getRepository(Specialization::class);
            $data_specialization = $rep->findOneBySlug($request->query->get('specialization'));
        }

        if(($request->query->get('city'))){
            $rep = $this->em->getRepository(City::class);
            $data_city = $rep->findOneBySlug($request->query->get('city'));
        }

       

        $builder
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
            "data"=>$data_city,
            "required"=>false,
            "mapped"=>false,
        ))
        ->add('specialization',EntityType::class,array(
            "class"=>Specialization::class,
            "attr"=>array(
                "class"=>"form-control-sm custom-select"
            ),
            "choice_label"=>function($el,$key,$index){
                return ucwords($el->getName());
            },
            'data' =>$data_specialization,
            "choice_value"=>"slug",
            'group_by' => function($el, $key, $index) {
                return strtoupper($el->getSlug()[0]);
            },
            "required"=>false,
            "mapped"=>false,
        ))
        ->add('name',TextType::class,[
            "required"=>false,
            "mapped"=>false,
        ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            "use_for_mode"=>"",
            'data_class' => Clinic::class
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
