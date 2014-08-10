<?php

namespace Piledge\DocumentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PiledgeDocumentBundle:Default:index.html.twig', array('name' => $name));
    }
}
