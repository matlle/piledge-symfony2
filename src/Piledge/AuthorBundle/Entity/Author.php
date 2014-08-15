<?php

namespace Piledge\AuthorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mappin\Annotation as Gedmo;
use Symfony\Component\Validator\ExecutionContextInterface;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * Author
 *
 * @ORM\Table(name="author")
 * @ORM\Entity(repositoryClass="Piledge\AuthorBundle\Entity\AuthorRepository")
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
     */
    private $author_username;

    /**
     * @var string
     *
     * @ORM\Column(name="author_password", type="string", length=255)
     */
    private $author_password;

    /**
     * @var string
     *
     * @ORM\Column(name="author_salt", type="string", length=255)
     */
    private $author_salt;

    /**
     * @var array
     *
     * @ORM\Column(name="author_roles", type="array")
     */
    private $author_roles;



    public function __construct() {

        $this->roles = array();
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

}
