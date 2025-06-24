<?php

// src/AppBundle/Repository/BookRepository.php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class BookRepository extends EntityRepository
{
    /**
     * Busca livros por título, ISBN, autor ou categoria.
     *
     * @param string $term
     * @return Book[]
     */
    public function search($term)
    {
        return $this->createQueryBuilder('b')
            ->join('b.author', 'a')
            ->join('b.category', 'c')
            ->where('b.title   LIKE :term')
            ->orWhere('b.isbn    LIKE :term')
            ->orWhere('a.name    LIKE :term')
            ->orWhere('c.title   LIKE :term')
            ->setParameter('term', '%'.$term.'%')
            ->orderBy('b.title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
