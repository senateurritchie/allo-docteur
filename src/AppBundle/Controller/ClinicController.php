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


/**
* @Route("/clinics", name="clinic_")
*/
class ClinicController extends Controller{


   /**
    * @Route("/{slug}", name="index", requirements={"slug":"([\w-]+)?"})
    */
    public function indexAction(Request $request,$slug=null){
        $query = array_merge($request->query->all(),["type"=>"clinic"]);
        return $this->forward('AppBundle:Search:index',["slug"=>$slug],$query);
    }
}