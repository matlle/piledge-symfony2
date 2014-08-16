<?php

namespace Piledge\DocumentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Piledge\DocumentBundle\Entity\Document;
use Piledge\DocumentBundle\Form\DocumentType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DocumentController extends Controller
{
    public function showAction($id)
    {
        return $this->render('PiledgeDocumentBundle:Document:show.html.twig', array('name' => $id));
    }

  
    /*
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

                $em = $this->getDoctrine()->getManager();
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
