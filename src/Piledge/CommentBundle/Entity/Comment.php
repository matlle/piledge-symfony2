<?php

namespace Piledge\CommentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="Piledge\CommentBundle\Entity\CommentRepository")
 */
class Comment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="comment_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $comment_id;


    /**
     * @ORM\ManyToOne(targetEntity="Piledge\AuthorBundle\Entity\Author")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="author_id", nullable=false)
     */
    private $author;


    /**
     * @ORM\ManyToOne(targetEntity="Piledge\DocumentBundle\Entity\Document")
     * @ORM\JoinColumn(name="document_id", referencedColumnName="document_id", nullable=false)
     */
    private $document;


    /**
     * @var string
     *
     * @ORM\Column(name="comment_body", type="text")
     * @Assert\NotBlank(message="The comment can't be empty. Please try again")
     */
    private $comment_body;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="comment_created_at", type="datetime")
     * @Assert\DateTime()
     */
    private $comment_created_at;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="comment_updated_at", type="datetime")
     * @Assert\Datetime()
     */
    private $comment_updated_at;


    
    public function __construct() {
        $this->comment_created_at = new \Datetime;
        $this->comment_updated_at = new \Datetime;
    }



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->comment_id;
    }

    /**
     * Set commentBody
     *
     * @param string $commentBody
     * @return Comment
     */
    public function setCommentBody($commentBody)
    {
        $this->comment_body = $commentBody;

        return $this;
    }

    /**
     * Get commentBody
     *
     * @return string 
     */
    public function getCommentBody()
    {
        return $this->comment_body;
    }

    /**
     * Set commentCreatedAt
     *
     * @param \DateTime $commentCreatedAt
     * @return Comment
     */
    public function setCommentCreatedAt($commentCreatedAt)
    {
        $this->comment_created_at = $commentCreatedAt;

        return $this;
    }

    /**
     * Get commentCreatedAt
     *
     * @return \DateTime 
     */
    public function getCommentCreatedAt()
    {
        return $this->comment_created_at;
    }

    /**
     * Set commentUpdatedAt
     *
     * @param \DateTime $commentUpdatedAt
     * @return Comment
     */
    public function setCommentUpdatedAt($commentUpdatedAt)
    {
        $this->comment_updated_at = $commentUpdatedAt;

        return $this;
    }

    /**
     * Get commentUpdatedAt
     *
     * @return \DateTime 
     */
    public function getCommentUpdatedAt()
    {
        return $this->comment_updated_at;
    }

    /**
     * Get comment_id
     *
     * @return integer 
     */
    public function getCommentId()
    {
        return $this->comment_id;
    }

    /**
     * Set author
     *
     * @param \Piledge\AuthorBundle\Entity\Author $author
     * @return Comment
     */
    public function setAuthor(\Piledge\AuthorBundle\Entity\Author $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \Piledge\AuthorBundle\Entity\Author 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set document
     *
     * @param \Piledge\DocumentBundle\Entity\Document $document
     * @return Comment
     */
    public function setDocument(\Piledge\DocumentBundle\Entity\Document $document)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get document
     *
     * @return \Piledge\DocumentBundle\Entity\Document 
     */
    public function getDocument()
    {
        return $this->document;
    }
}
