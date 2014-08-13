<?php

namespace Piledge\DocumentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Piledge\DocumentBundle\Entity\Document;
use Piledge\DocumentBundle\Form\DocumentType;


class DocumentController extends Controller
{
    public function showAction($id)
    {
        return $this->render('PiledgeDocumentBundle:Document:show.html.twig', array('name' => $id));
    }


    public function uploadAction() {

        $document = new Document;
        $form = $this->createForm(new DocumentType, $document);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($document);
                $em->flush();

                return $this->redirect($this->generateUrl('piledgeGle_homepage'));
            }
        }

        return $this->render('PiledgeDocumentBundle:Document:upload.html.twig', array(
               'form' => $form->createView()
                ));
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
