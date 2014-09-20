<?php

namespace Piledge\DocumentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Piledge\DocumentBundle\Entity\Document;
use Piledge\CommentBundle\Entity\Comment;

use Symfony\Component\Form\Form;
use Piledge\DocumentBundle\Form\DocumentType;
use Piledge\CommentBundle\Form\CommentType;

use JMS\SecurityExtraBundle\Annotation\Secure;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DocumentController extends Controller
{
    public function showAction($doc_id, Form $comment_form = null, $errors = '')
    {
        $repdoc = $this->getDoctrine()
                       ->getManager()
                       ->getRepository('PiledgeDocumentBundle:Document');

        $document = $repdoc->findOneByAuthor($doc_id);

        $comments = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('PiledgeCommentBundle:Comment')
                         ->findAllByAuthorAndDocument($doc_id);

        $doc_data = [];
        
        foreach($document as $doc) {
            $doc_data = $doc;
        }

        
        if ($comment_form === null) {
            $comment = new Comment;
            $comment_form = $this->createForm(new CommentType, $comment);
        }

        if (count($errors) == 0) {
            $errors = '';
        }


        //return new Response("The result is: ".print_r($document));

        return $this->render('PiledgeDocumentBundle:Document:show.html.twig', array(
            'doc' => $doc_data,
            'coms' => $comments,
            'comment_form' => $comment_form->createView(),
            'errors' => $errors

        ));
    }


  
    /**
     * @Security("has_role('ROLE_AUTHOR')")
     */
    public function uploadAction() {
        
        $document = new Document;
        $form = $this->createForm(new DocumentType, $document);

        $request = $this->get('request');
        $validator = $this->get('validator');
        $errors = ''; 
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            $errors = $validator->validate($document);


            if ($form->isValid()) {
                
                $user = $this->getUser();

                $em = $this->getDoctrine()->getManager();
                $document->setAuthor($user);
                $em->persist($document);
                $em->flush();

                return $this->redirect($this->generateUrl('piledgeGle_homepage'));
            }
        }
        
        if (count($errors) > 0) {       

            return $this->render('PiledgeDocumentBundle:Document:upload.html.twig', array(
                'form' => $form->createView(),
                'errors' => $errors
               ));

        }else {

            return $this->render('PiledgeDocumentBundle:Document:upload.html.twig', array(
               'form' => $form->createView()
                ));
        }

    }



    public function downloadAction($doc_name) {

         /*$fichier = "nomFichier.pdf";
            $chemin = "bundles/nomBundle/.../"; // emplacement de votre fichier .pdf
                  
            $response = new Response();
               $response->setContent(file_get_contents($chemin.$fichier));
               $response->headers->set('Content-Type', 'application/force-download'); // modification du content-type pour forcer le téléchargement (sinon le navigateur internet essaie d'afficher le document)
                  $response->headers->set('Content-disposition', 'filename='. $fichier);
                        
               return $response;*/


   /*return new Response('', 200, array(
           'X-Sendfile'          => $filename,
               'Content-type'        => 'application/octet-stream',
                   'Content-Disposition' => sprintf('attachment; filename="%s"', $filename))
               ));*/


        //return new Response("");
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
