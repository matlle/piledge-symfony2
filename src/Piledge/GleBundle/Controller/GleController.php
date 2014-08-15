<?php

namespace Piledge\GleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Piledge\DocumentBundle\Entity\Document;

class GleController extends Controller
{
    public function indexAction() {

        $doc_repo = $this->getDoctrine()
                          ->getManager()
                          ->getRepository('PiledgeDocumentBundle:Document');
        $documents = $doc_repo->findByDateDesc(); 

        return $this->render('PiledgeGleBundle:Gle:index.html.twig', array(
                     'documents' => $documents
                 ));
    }
}
