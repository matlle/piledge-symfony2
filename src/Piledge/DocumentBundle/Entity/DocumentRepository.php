<?php

namespace Piledge\DocumentBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * DocumentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DocumentRepository extends EntityRepository {

    public function findByDateDesc() {

        $qb = $this->createQueryBuilder('d')
                   ->join('d.author', 'a')
                   ->addSelect('a')
                   ->orderBy('d.document_created_at', 'DESC');

        return $qb->getQuery()->getArrayResult();
    }


    public function findOneByAuthor($id) {

        $qb = $this->createQueryBuilder('d')
                   ->join('d.author', 'a')
                   ->addSelect('a')
                   ->where('d.document_id = :id')
                   ->setParameter('id', $id);

        return $qb->getQuery()->getArrayResult();
   }

}
