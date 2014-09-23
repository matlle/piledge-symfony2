<?php

namespace Piledge\MessageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Piledge\MessageBundle\Entity\Message;

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
}
