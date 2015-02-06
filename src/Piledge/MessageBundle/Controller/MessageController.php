<?php

namespace Piledge\MessageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Piledge\MessageBundle\Entity\Message;
use Piledge\MessageBundle\Form\MessageType;

class MessageController extends Controller
{
    public function inboxAction()
    {
        $msg_repo = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('PiledgeMessageBundle:Message');

        $msg_unread = $msg_repo->findMsg_unread();

        $inbox_msg = $msg_repo->findAll();

        return $this->render('PiledgeMessageBundle:Message:msg.html.twig', array(
                   'msg_unread' => $msg_unread,
                   'inbox_msg' => $inbox_msg
               ));
    }


    
    public function nmsgAction() {

        $msg_repo = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('PiledgeMessageBundle:Message');

        $msg_unread = $msg_repo->findMsg_unread();

        $inbox_msg = $msg_repo->findAll();

        $msg = new Message;
        $form = $this->createForm(new MessageType, $msg);

        $request = $this->get('request');
        $validator = $this->get('validator');
        $errors = '';

        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            $errors = $validator->validate($msg);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($msg);
                $em->flush();

                return $this->redirect($this->generateUrl('PiledgeMessage_inbox'));
            }
        }

        if(count($errors) > 0) {

            return $this->render('PiledgeMessageBundle:Message:nmsg.html.twig', array(
                'form' => $form->createView(),
                'msg_unread' => $msg_unread,
                'inbox_msg' => $inbox_msg,
                'errors' => $errors
            ));

        } else {
            return $this->render('PiledgeMessageBundle:Message:nmsg.html.twig', array(
                'form' => $form->createView(),
                'msg_unread' => $msg_unread,
                'inbox_msg' => $inbox_msg
            ));
       }
    }

    
    public function sentAction() {
    }




}
