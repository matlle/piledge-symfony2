<?php

namespace Piledge\DocumentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Piledge\DocumentBundle\Entity\Document;
use Piledge\DocumentBundle\Form\DocumentType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DocumentController extends Controller
{
    public function showAction($id)
    {
        $repdoc = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('PiledgeDocumentBundle:Document');

        $document = $repdoc->findOneByAuthor($id);

        $doc_data = [];
        
        foreach($document as $doc) {
            $doc_data = $doc;
        }

        //return new Response("The result is: ".print_r($document));

        return $this->render('PiledgeDocumentBundle:Document:show.html.twig', array(
                 'doc' => $doc_data
        ));
    }


  
    /**
     * @Security("has_role('ROLE_AUTHOR')")
     */
    public function uploadAction() {
        
        $document = new Document;
        $form = $this->createForm(new DocumentType, $document);

        $request = $this->get('request');
        $validator = $this->get('validator');
        $errors = ''; 
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            $errors = $validator->validate($document);


            if ($form->isValid()) {
                
                $user = $this->getUser();

                $em = $this->getDoctrine()->getManager();
                $document->setAuthor($user);
                $em->persist($document);
                $em->flush();

                return $this->redirect($this->generateUrl('piledgeGle_homepage'));
            }
        }
        
        if (count($errors) > 0) {       

            return $this->render('PiledgeDocumentBundle:Document:upload.html.twig', array(
                'form' => $form->createView(),
                'errors' => $errors
               ));

        }else {

            return $this->render('PiledgeDocumentBundle:Document:upload.html.twig', array(
               'form' => $form->createView()
                ));
        }

    }

    
    public function updateAction($id)
    {
        return $this->render('PiledgeDocumentBundle:Document:update.html.twig', array('name' => $id));
    }


    public function removeAction($id)
    {
        return $this->render('PiledgeDocumentBundle:Document:remove.html.twig', array('name' => $id));
    }
}
