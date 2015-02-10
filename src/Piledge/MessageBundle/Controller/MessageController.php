<?php

namespace Piledge\MessageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Piledge\MessageBundle\Entity\Message;
use Piledge\MessageBundle\Entity\Author;
use Piledge\MessageBundle\Form\MessageType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class MessageController extends Controller
{   
    /**
     * @Security("has_role('ROLE_AUTHOR')")
     */
    public function inboxAction()
    {
        $msg_repo = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('PiledgeMessageBundle:Message');

        $nb_msg_unread = $msg_repo->findMsg_unread($this->getUser()->getAuthorId());

        $inbox_msg = $msg_repo->findInbox($this->getUser()->getAuthorId());

        return $this->render('PiledgeMessageBundle:Message:msg.html.twig', array(
                   'nb_msg_unread' => $nb_msg_unread,
                   'inbox_msg' => $inbox_msg
               ));
    }


    
    /**
     * @Security("has_role('ROLE_AUTHOR')")
     */
    public function nmsgAction() {

        $msg_repo = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('PiledgeMessageBundle:Message');

        $nb_msg_unread = $msg_repo->findMsg_unread($this->getUser()->getAuthorId());

        $inbox_msg = $msg_repo->findInbox($this->getUser()->getAuthorId());

        $msg = new Message;
        $form = $this->createForm(new MessageType, $msg);

        $request = $this->get('request');
        $validator = $this->get('validator');
        $errors = '';

        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            $errors = $validator->validate($msg);

                if ($form->isValid()) {
                $em_msg = $this->getDoctrine()->getManager();
                $em_msg->persist($msg);

                $author_receiver = $this->getDoctrine()
                                        ->getManager()
                                        ->getRepository('PiledgeAuthorBundle:Author')
                                        ->findOneByUsername($msg->getMessageReceiverUsername());

                $author = $this->getUser();
                $msg->setAuthor($author);

                $msg->setMessageReceiverId($author_receiver->getAuthorId());
                $em_msg->flush();

                return $this->redirect($this->generateUrl('piledgeMessage_inbox'));

            }

     }

        if(count($errors) > 0) {

            return $this->render('PiledgeMessageBundle:Message:nmsg.html.twig', array(
                'form' => $form->createView(),
                'nb_msg_unread' => $nb_msg_unread,
                'inbox_msg' => $inbox_msg,
                'errors' => $errors
            ));

        } else {
            return $this->render('PiledgeMessageBundle:Message:nmsg.html.twig', array(
                'form' => $form->createView(),
                'nb_msg_unread' => $nb_msg_unread,
                'inbox_msg' => $inbox_msg
            ));
       }
    }

    
    /**
     * @Security("has_role('ROLE_AUTHOR')")
     */
    public function sentAction() {
    }




}
