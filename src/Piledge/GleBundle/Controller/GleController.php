<?php

namespace Piledge\GleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Piledge\DocumentBundle\Entity\Document;

class GleController extends Controller {

    public function mainAction() {
        
        $documents = $this->getDoctrine()
                          ->getManager()
                          ->getRepository('PiledgeDocumentBundle:Document')
                          ->findByDateDesc();
        $nb_msg_unread = 0;    
        if($this->getUser()) {

            $nb_msg_unread = $this->getDoctrine()
                              ->getManager()
                              ->getRepository('PiledgeMessageBundle:Message')
                              ->findMsg_unread($this->getUser()->getAuthorId());
        }


        return $this->render('PiledgeGleBundle:Gle:main.html.twig', array(
                     'documents' => $documents,
                     'nb_msg_unread' => $nb_msg_unread
                 ));
    }
}
