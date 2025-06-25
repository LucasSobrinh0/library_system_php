<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Book;
use AppBundle\Entity\Reader;
use AppBundle\Entity\Loan;

class DashboardController extends Controller{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function indexAction(){
       $em = $this->getDoctrine()->getManager();

       // Totais
       $totalLivros = $em->getRepository(Book::class)->createQueryBuilder('b')->select('count(b.id)')->getQuery()->getSingleScalarResult();
       $totalLeitores = $em->getRepository(Reader::class)->createQueryBuilder('r')->select('count(r.id)')->getQuery()->getSingleScalarResult();
       $emprestimosAtivos = $em->getRepository(Loan::class)->createQueryBuilder('l')->select('count(l.id)')->where('l.status = :status')
       ->setParameter('status', 'NOT_RETURNED')->getQuery()->getSingleScalarResult();

       // Ultimos Livros
       $ultimosLivros = $em->getRepository(Book::class)->createQueryBuilder('b')->orderBy('b.id', 'DESC')
       ->setMaxResults(5)->getQuery()->getResult();

       $ultimosEmprestimos = $em->getRepository(Loan::class)->createQueryBuilder('l')->orderBy('l.id', 'DESC')->setMaxResults(5)
       ->getQuery()->getResult();

       // Renderizar

       return $this->render('Dashboard/index.html.twig', [
        'totalLivros' => $totalLivros,
        'totalLeitores' => $totalLeitores,
        'emprestimosAtivos' => $emprestimosAtivos,
        'ultimosLivros' => $ultimosLivros,
        'ultimosEmprestimos' => $ultimosEmprestimos
       ]);
    }
}