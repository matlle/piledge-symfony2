<?php

namespace Piledge\MessageBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * MessageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MessageRepository extends EntityRepository
{

    public function findMsg_unread() {
        
        $qb = $this->createQueryBuilder('m')
                   ->where('m.message_is_read = false');

        return $qb->getQuery()
                  ->getResult();
    }


    public function findInbox() {

        return '';
    }
}