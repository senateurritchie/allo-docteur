<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

use AppBundle\Entity\Specialization;
use AppBundle\Entity\Clinic;
use AppBundle\Entity\Doctor;

class DefaultController extends Controller{


    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        //$rep_doctor = $em->getRepository(Doctor::class);
       
       /* //  on charge les docteurs
        $ev = array(
        );
        $programmes = $rep_movie->findBy($ev,["id"=>"desc"],4);

        //  on charge les categories des docteurs
        $rep = $em->getRepository(Actor::class);
        $actors = $rep->findBy([],["id"=>"desc"],4);*/


        return $this->render('default/index.html.twig',array(
           
        ));
    }


    /**
    * @Route("/a-propos-de-nous/", name="about_us")
    */
    public function aboutUsAction(Request $request){
        return $this->render('default/about-us.html.twig');
    }

    /**
    * @Route("/faq/", name="faq")
    */
    public function faqAction(Request $request){
        return $this->render('default/faq.html.twig');
    }


    /**
    * @Route("/conditions-generales-d-utilisation", name="cgu")
    */
    public function cguAction(){
        return $this->render('default/cgu.html.twig');
    }

    /**
    * @Route("/politique-de-confidentialite", name="privacy_policy")
    */
    public function privacyPolicyAction(){
        return $this->render('default/privacy-policy.html.twig');
    }

    public function renderFooter(){
        $em = $this->getDoctrine()->getManager();

        /*//  on charge les categories
        $rep = $em->getRepository(Category::class);
        $categories = $rep->findBy([],["name"=>"asc"]);*/

        return $this->render('footer.html.twig',array(
            //"categories"=>$categories,
        ));
    }
}
