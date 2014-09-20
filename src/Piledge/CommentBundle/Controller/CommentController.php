<?php

namespace Piledge\CommentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Piledge\DocumentBundle\Entity\Document;
use Piledge\CommentBundle\Entity\Comment;

use Symfony\Component\Form\Form;
use Piledge\CommentBundle\Form\CommentType;

use JMS\SecurityExtraBundle\Annotation\Secure;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CommentController extends Controller
{
    public function addAction($doc_id)
    {

        $author = $this->getDoctrine()
                       ->getManager()
                       ->getRepository('PiledgeDocumentBundle:Document')
                       ->find($doc_id);

        $comment = new Comment;
        $form = $this->createForm(new CommentType, $comment);
        $request = $this->get('request');

        $form->bind($request);
        $validator = $this->get('validator');
        $errors = $validator->validate($comment);

        if ($form->isValid()) {
            $user = $this->getUser();
            $em = $this->getDoctrine()->getManager();
            $comment->setAuthor($user);
            $comment->setDocument($author);
            $em->persist($comment);
            $em->flush();

            return $this->redirect($this->generateUrl('piledgeDocument_show', array(
                'doc_id' => $doc_id
            ))); 
        }

        return $this->forward('PiledgeDocumentBundle:Document:show', array(
            'doc_id' => $doc_id,
            'form' => $form,
            'errors' => $errors
        ));

    }
  
}
