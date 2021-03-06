<?php

namespace Piledge\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="Piledge\MessageBundle\Entity\MessageRepository")
 */
class Message
{
    /**
     * @var integer
     *
     * @ORM\Column(name="message_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $message_id;


    /**
     * @ORM\ManyToOne(targetEntity="Piledge\AuthorBundle\Entity\Author")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="author_id", nullable=false)
     */
    private $author;


    /**
     * @var integer
     *
     * @ORM\Column(name="message_receiver_id", type="integer")
     */
    private $message_receiver_id;

    /**
     * @var string
     *
     * @ORM\Column(name="message_receiver_username", type="string", length=255)
     * @Assert\NotBlank(message="The username can't be empty. Please try again")
     */
    private $message_receiver_username;

    
    /**
     * @var string
     *
     * @ORM\Column(name="message_subject", type="string", length=255, nullable=true)
     */
    private $message_subject;

    /**
     * @var integer
     *
     * @ORM\Column(name="message_is_read", type="boolean")
     */
    private $message_is_read;

    /**
     * @var string
     *
     * @ORM\Column(name="message_content", type="text")
     * @Assert\NotBlank(message="The content of the message can't be empty. Please try again")
     */
    private $message_content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="message_created_at", type="datetime")
     */
    private $message_created_at;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="message_updated_at", type="datetime")
     */
    private $message_updated_at;



    public function __construct() {
        $this->message_is_read = false;
        $this->message_created_at = new \Datetime;
        $this->message_updated_at = new \Datetime;
    }


    /**
     * Set messageReceiverId
     *
     * @param integer $messageReceiverId
     * @return Message
     */
    public function setMessageReceiverId($messageReceiverId)
    {
        $this->message_receiver_id = $messageReceiverId;

        return $this;
    }

    /**
     * Get messageReceiverId
     *
     * @return integer 
     */
    public function getMessageReceiverId()
    {
        return $this->message_receiver_id;
    }

    /**
     * Set messageReceiverUsername
     *
     * @param string $messageReceiverUsername
     * @return Message
     */
    public function setMessageReceiverUsername($messageReceiverUsername)
    {
        $this->message_receiver_username = $messageReceiverUsername;

        return $this;
    }

    /**
     * Get messageReceiverUsername
     *
     * @return string 
     */
    public function getMessageReceiverUsername()
    {
        return $this->message_receiver_username;
    }


    /**
     * Set messageSubject
     *
     * @param string $messageSubject
     * @return Message
     */
    public function setMessageSubject($messageSubject)
    {
        $this->message_subject = $messageSubject;

        return $this;
    }

    /**
     * Get messageSubject
     *
     * @return string 
     */
    public function getMessageSubject()
    {
        return $this->message_subject;
    }

    /**
     * Set messageIsRead
     *
     * @param integer $messageIsRead
     * @return Message
     */
    public function setMessageIsRead($messageIsRead)
    {
        $this->message_is_read = $messageIsRead;

        return $this;
    }

    /**
     * Get messageIsRead
     *
     * @return integer 
     */
    public function getMessageIsRead()
    {
        return $this->message_is_read;
    }

    /**
     * Set messageContent
     *
     * @param string $messageContent
     * @return Message
     */
    public function setMessageContent($messageContent)
    {
        $this->message_content = $messageContent;

        return $this;
    }

    /**
     * Get messageContent
     *
     * @return string 
     */
    public function getMessageContent()
    {
        return $this->message_content;
    }

    /**
     * Set messageCreatedAt
     *
     * @param \DateTime $messageCreatedAt
     * @return Message
     */
    public function setMessageCreatedAt($messageCreatedAt)
    {
        $this->message_created_at = $messageCreatedAt;

        return $this;
    }

    /**
     * Get messageCreatedAt
     *
     * @return \DateTime 
     */
    public function getMessageCreatedAt()
    {
        return $this->message_created_at;
    }

    /**
     * Set messageUpdatedAt
     *
     * @param \DateTime $messageUpdatedAt
     * @return Message
     */
    public function setMessageUpdatedAt($messageUpdatedAt)
    {
        $this->message_updated_at = $messageUpdatedAt;

        return $this;
    }

    /**
     * Get messageUpdatedAt
     *
     * @return \DateTime 
     */
    public function getMessageUpdatedAt()
    {
        return $this->message_updated_at;
    }

    /**
     * Get message_id
     *
     * @return integer 
     */
    public function getMessageId()
    {
        return $this->message_id;
    }

    /**
     * Set author
     *
     * @param \Piledge\AuthorBundle\Entity\Author $author
     * @return Message
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

    public function isUsernameExist() {
        $username = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('PiledgeAuthorBundle:Author')
                         ->findOneByUsername($this->message_receiver_username);
        if($username == null) return false;
        else return true;
    }

}
