<?php

namespace Piledge\DocumentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DocumentController extends Controller
{
    public function showAction($id)
    {
        return $this->render('PiledgeDocumentBundle:Document:show.html.twig', array('name' => $id));
    }


    public function uploadAction()
    {
        return $this->render('PiledgeDocumentBundle:Document:upload.html.twig');
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
