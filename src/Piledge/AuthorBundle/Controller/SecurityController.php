<?php

namespace Piledge\AuthorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;


class AuthorController extends Controller {

    public function loginAction() {

        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {

            return $this->redirect($this->generateUrl('piledgeGle_homepage'));
        }

        $request = $this->getRequest();
        $session = $request->getSession();

        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {

            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);

        } else {

            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render('PiledgeAuthorBundle:Author:login.html.twig', array(
                 'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                 'error' => $error,
             ));
   
    }

}
