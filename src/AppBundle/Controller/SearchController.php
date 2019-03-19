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

use AppBundle\Form\DoctorSearchType;
use AppBundle\Form\ClinicSearchType;



/**
* @Route("/search", name="search_")
*/
class SearchController extends Controller{


   /**
    * @Route("/{slug}", name="index", requirements={"slug":"([\w-]+)?"})
    */
    public function indexAction(Request $request,$slug=null){

        $em = $this->getDoctrine()->getManager();
        $type = $request->query->get('type');

        switch ($type) {
            case 'clinic':
                $subject_type = ClinicSearchType::class;
                $subject = new Clinic();
                $subject_class = Clinic::class;
            break;
            
            default:
                $subject_type = DoctorSearchType::class;
                $subject = new Doctor();
                $subject_class = Doctor::class;
            break;
        }


        $rep = $em->getRepository($subject_class);

        $limit = intval($request->query->get('limit',20));
        $offset = intval($request->query->get('offset',0));

        $limit = $limit > 20 ? 20 : $limit;
        $offset = $offset < 0 ? 0 : $offset;
         

        if($slug){

            if(!($data = $rep->search(["slug"=>$slug]))) {
                throw $this->createNotFoundException("Le sujet recherché est introuvable");
            }

            return $this->render('doctor/single.html.twig',array(
                "data"=>$data[0]
            ));
        }

        $form = $this->createForm($subject_type,$subject,[
            "use_for_mode"=>"index_search_page",
            "method"=>"GET"
        ]);
        $params = $request->query->all();

        $data = $rep->search($params,$limit,$offset);

        if($request->isXmlHttpRequest()){

            $acceptHeader = AcceptHeader::fromString($request->headers->get('Accept'));
            if ($acceptHeader->has('text/html')) {
                $item = $acceptHeader->get('text/html');
                $charset = $item->getAttribute('charset', 'utf-8');
                $quality = $item->getQuality();

                return $this->render('doctor/item-render.html.twig',array(
                    "data"=>$data,
                ));
            }

            if(intval(@$params['id'])){
                if(empty($data)){
                    throw $this->createNotFoundException("sujet introuvable");
                }
                $data = $data[0];
            }

            $json = json_decode($this->get("serializer")->serialize($data,'json',array('groups' => array('group1'))),true);
        

            $json = json_encode($json);
            $response = new Response($json);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        return $this->render('doctor/index.html.twig',array(
            "form"=>$form->createView(),
            "data"=>$data,
        ));
    }
}
