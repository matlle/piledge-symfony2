<?php

namespace Piledge\AuthorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;


use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;


use Piledge\AuthorBundle\Entity\Signup;
use Piledge\AuthorBundle\Entity\Author;
use Piledge\AuthorBundle\Form\SignupType;


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



    public function signupAction() {

        $signup = new Signup;
        $form = $this->createForm(new SignupType, $signup);
        
        $request = $this->get('request');

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            $factory = $this->get('security.encoder_factory');
            $author = new Author;
            $encoder = $factory->getEncoder($author);
            $password = $encoder->encodePassword($signup->getAuthor()->getAuthorPassword(), $signup->getAuthor()->getAuthorSalt());
            $signup->getAuthor()->setAuthorPassword($password);

            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($signup->getAuthor());
                $em->flush();

                $repo = $this->getDoctrine()->getRepository("PiledgeAuthorBundle:Author");
                $user = $repo->findOneByUsername($signup->getAuthor()->getAuthorUsername());

                if(!$user)
                    throw new UsernameNotFoundException("User not found");
                else {
                    $token = new UsernamePasswordToken($user[0], null, "guard", $user[0]->getRoles());
                    $this->get("security.context")->setToken($token);

                    $request = $this->get("request");
                    $event = new InteractiveLoginEvent($request, $token);
                    $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

                    return $this->redirect($this->generateUrl('piledgeGle_homepage'));
                }
            }
        }
        
            return $this->render('PiledgeAuthorBundle:Author:signup.html.twig', array(
                   'form' => $form->createView()
                  ));

    }

   

    public function feedAction($username) {
        return new Response("New author's feed!");
    }


    public function profileAction($username) {
        return new Response("Author's profile!");
    }


    public function restorePasswordAction() {
    }


    
    public function giveRolesAction(Array $roles) {
    }

    
    public function deleteAction() {
    }



}
