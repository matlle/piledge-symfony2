<?php

namespace Piledge\AuthorBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Piledge\AuthorBundle\Entity\Author;

class Signup 

{
     
    /**
     * @Assert\Type(type="Piledge\AuthorBundle\Entity\Author")
     */
    protected $author;



    public function setAuthor(Author $author) {

        $this->author = $author;

        return $this;
    }

    public function getAuthor() {

        return $this->author;
    }

}
