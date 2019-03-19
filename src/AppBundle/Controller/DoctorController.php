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
use AppBundle\Entity\User;


/**
* @Route("/doctors", name="doctor_")
*/
class DoctorController extends Controller{

    /**
    * @Route("/{slug}", name="index", requirements={"slug":"([\w-]+)?"})
    */
    public function indexAction(Request $request,$slug=null){
        $query = array_merge($request->query->all(),["type"=>"doctor"]);
        return $this->forward('AppBundle:Search:index',["slug"=>$slug],$query);
    }

    
    /**
    * @Route("/{slug}/booking/start", name="booking_start", requirements={"slug":"([\w-]+)"})
    */
    public function bookingStartAction(Request $request,$slug){

        $em = $this->getDoctrine()->getManager();
        //$rep_doctor = $em->getRepository(Doctor::class);
       
       /* //  on charge les docteurs
        $ev = array(
        );
        $programmes = $rep_movie->findBy($ev,["id"=>"desc"],4);

        //  on charge les categories des docteurs
        $rep = $em->getRepository(Actor::class);
        $actors = $rep->findBy([],["id"=>"desc"],4);*/

        
        return $this->redirectToRoute('doctor_booking_save',['slug'=>$slug,"booking_id"=>User::generateToken(10)]);

    }

    /**
    * @Route("/{slug}/booking/{booking_id}/save", name="booking_save", requirements={"slug":"([\w-]+)","booking_id":"([\w-]+)"})
    */
    public function bookingSaveAction(Request $request,$slug,$booking_id){

        $em = $this->getDoctrine()->getManager();
        //$rep_doctor = $em->getRepository(Doctor::class);
       
       /* //  on charge les docteurs
        $ev = array(
        );
        $programmes = $rep_movie->findBy($ev,["id"=>"desc"],4);

        //  on charge les categories des docteurs
        $rep = $em->getRepository(Actor::class);
        $actors = $rep->findBy([],["id"=>"desc"],4);*/

        if($request->isMethod('POST')){
            return $this->redirectToRoute('doctor_booking_finish',['slug'=>$slug,"booking_id"=>$booking_id]);
        }

       return $this->render('doctor/booking-start.html.twig',array());
    }

    /**
    * @Route("/{slug}/booking/{booking_id}/finish", name="booking_finish", requirements={"slug":"([\w-]+)","booking_id":"([\w-]+)"})
    */
    public function bookingFinishAction(Request $request,$slug,$booking_id){

        $em = $this->getDoctrine()->getManager();
       
        return $this->render('doctor/booking-finish.html.twig',array());
    }

    
}
