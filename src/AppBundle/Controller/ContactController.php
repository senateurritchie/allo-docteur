<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\WebsiteMailType;
use AppBundle\Entity\WebsiteMail;


class ContactController extends Controller{

   /**
	* @Route("/nous-contacter/", name="contact_us")
	*/
    public function indexAction(Request $request, \Swift_Mailer $mailer){
        $em = $this->getDoctrine()->getManager();

    	$clientMail = new WebsiteMail();
    	$clientMail->setCreateAt(new \Datetime());

    	$form = $this->createForm(WebsiteMailType::class,$clientMail);

    	$form->handleRequest($request);

    	if($form->isSubmitted() && $form->isValid()){
    		
            $adminInfo = $this->getParameter("admin");

	        // message a envoyer au visiteur
    		$message = (new \Swift_Message($clientMail->getSubject()))
	        ->setFrom([$adminInfo["email"]=>"Allô Docteur"])
	        ->setTo($clientMail->getEmail())
	        ->setBody(
	            $this->renderView(
	                'contact/email/visitor.html.twig',
	                array('mail' => $clientMail)
	            ),
	            'text/html'
	        );

	       	// message a envoyer au service contacté
	       	$from_name = $clientMail->getFirstname()." ".$clientMail->getLastname();
	       	
	        $message2 = (new \Swift_Message($clientMail->getSubject()))
	        ->setFrom([$clientMail->getEmail()=>$from_name])
	        ->setTo($adminInfo["email"])
	        ->setBody(
	            $this->renderView(
	                'contact/email/webmaster.html.twig',
	                array('mail' => $clientMail)
	            ),
	            'text/html'
	        );

            try {
                $mailer->send($message);
                $mailer->send($message2);
            } catch (\Exception $e) {
                
            }
	    	
	    	$em->persist($clientMail);
	    	$em->flush();

    		$this->addFlash("notice-success",1);
    		return $this->redirectToRoute("contact_us");
    	}

        return $this->render('contact/index.html.twig',array(
        	"form"=>$form->createView(),
        ));
    }
}
