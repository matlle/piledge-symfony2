<?php

namespace Piledge\GleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GleController extends Controller
{
    public function indexAction()
    {
        return $this->render('PiledgeGleBundle:Gle:index.html.twig');
    }
}
