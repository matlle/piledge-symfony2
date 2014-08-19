<?php

namespace Piledge\AuthorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mappin\Annotation as Gedmo;
use Symfony\Component\Validator\ExecutionContextInterface;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * Author
 *
 * @ORM\Table(name="author")
 * @ORM\Entity(repositoryClass="Piledge\AuthorBundle\Entity\AuthorRepository")
 * @UniqueEntity(fields="author_username", message="This username is already taken. Try again please")
 * @UniqueEntity(fields="author_email", message="This email is already taken. Try again please")
 * @ORM\HasLifecycleCallbacks()
 */
class Author implements UserInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="author_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $author_id;

    /**
     * @var string
     *
     * @ORM\Column(name="author_username", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $author_username;

    /**
     * @var string
     *
     * @ORM\Column(name="author_email", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $author_email;


    /**
     * @var string
     *
     * @ORM\Column(name="author_password", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=6,
     *     max=18,
     *     minMessage="This password is too short. It should have {{ limit }} characters or more.",
     *     maxMessage="This value is too long. It should have {{ limit }} characters or least.")
     */
    private $author_password;

    /**
     * @var string
     *
     * @ORM\Column(name="author_salt", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $author_salt;

    /**
     * @var array
     *
     * @ORM\Column(name="author_roles", type="array")
     * @Assert\NotBlank()
     */
    private $author_roles;



    public function __construct() {

        $this->author_roles = array('ROLE_AUTHOR');
        $this->author_salt = md5(uniqid(null, true));
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getAuthorId()
    {
        return $this->author_id;
    }

    /**
     * Set authorUsername
     *
     * @param string $authorUsername
     * @return Author
     */
    public function setAuthorUsername($authorUsername)
    {
        $this->author_username = $authorUsername;

        return $this;
    }

    /**
     * Get authorUsername
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->author_username;
    }

    /**
     * Set authorPassword
     *
     * @param string $authorPassword
     * @return Author
     */
    public function setAuthorPassword($authorPassword)
    {
        $this->author_password = $authorPassword;

        return $this;
    }

    /**
     * Get authorPassword
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->author_password;
    }

    /**
     * Set authorSalt
     *
     * @param string $authorSalt
     * @return Author
     */
    public function setAuthorSalt($authorSalt)
    {
        $this->author_salt = $authorSalt;

        return $this;
    }

    /**
     * Get authorSalt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->author_salt;
    }

    /**
     * Set authorRoles
     *
     * @param array $authorRoles
     * @return Author
     */
    public function setAuthorRoles($authorRoles)
    {
        $this->author_roles = $authorRoles;

        return $this;
    }

    /**
     * Get authorRoles
     *
     * @return array 
     */
    public function getRoles()
    {
        return $this->author_roles;
    }


    public function eraseCredentials() {
    }



    /**
     * Set author_email
     *
     * @param string $authorEmail
     * @return Author
     */
    public function setAuthorEmail($authorEmail)
    {
        $this->author_email = $authorEmail;

        return $this;
    }

    /**
     * Get author_email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->author_email;
    }


    /**
     * Get author_username
     *
     * @return string 
     */
    public function getAuthorUsername()
    {
        return $this->author_username;
    }

    /**
     * Get author_email
     *
     * @return string 
     */
    public function getAuthorEmail()
    {
        return $this->author_email;
    }

    /**
     * Get author_password
     *
     * @return string 
     */
    public function getAuthorPassword()
    {
        return $this->author_password;
    }

    /**
     * Get author_salt
     *
     * @return string 
     */
    public function getAuthorSalt()
    {
        return $this->author_salt;
    }

    /**
     * Get author_roles
     *
     * @return array 
     */
    public function getAuthorRoles()
    {
        return $this->author_roles;
    }
}
