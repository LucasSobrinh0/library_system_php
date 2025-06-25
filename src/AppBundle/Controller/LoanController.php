<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Loan;
use AppBundle\Form\LoanType;
use AppBundle\Entity\Book;
use AppBundle\Entity\Reader;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("loan")
 */
class LoanController extends Controller
{
/**
     * @Route("/", name="loan_index")
     */
    public function indexAction(Request $request)
    {
        $q = trim($request->query->get('q'));
        $loanDate = $request->query->get('loanDate');
        $returnDate = $request->query->get('returnDate');

        $qb = $this->getDoctrine()
                   ->getRepository(Loan::class)
                   ->createQueryBuilder('r')
                   ->leftJoin('r.book', 'b')
                   ->leftJoin('b.author', 'a');

        if ($q !== '') {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('b.title', ':q'),
                    $qb->expr()->like('a.name', ':q')
                )
            )
            ->setParameter('q', '%'.$q.'%');
}
        if (!empty($loanDate)){
            $qb->andWhere('r.loanDate = :loanDate')->setParameter('loanDate', new \DateTime($loanDate));
        }

        if (!empty($returnDate)){
            $qb->andWhere('r.returnDate = :returnDate')->setParameter('returnDate', new \DateTime($returnDate));
        }
        $loans = $qb
            ->orderBy('b.title', 'ASC')
            ->getQuery()
            ->getResult();
        $deleteForms = [];
        foreach ($loans as $loan) {
            $deleteForms[$loan->getId()] = $this->createDeleteForm($loan)->createView();
        }

        return $this->render('Loan/index.html.twig', [
            'loans' => $loans,
            'delete_forms' => $deleteForms
        ]);
    }

    /**
     * @Route("/new", name="loan_new")
     */
    public function newAction(Request $request)
    {
        $em= $this->getDoctrine()->getManager();
        $loan = new Loan();
        $form = $this->createForm(LoanType::class, $loan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // verifica se já existe um empréstimo ativo para este livro
            $existing = $em->getRepository(Loan::class)
                ->findOneBy([
                    'book'       => $loan->getBook(),
                    'status' => 'NOT_RETURNED',
                ])
            ;

            if ($existing) {
                $this->addFlash('error', sprintf(
                'O livro “%s” já está emprestado desde %s.',
                $existing->getBook()->getTitle(),
                $existing->getLoanDate()->format('d/m/Y')
            ));

                // nesse caso, apenas re-renderiza o formulário:
                return $this->render('Loan/new.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            // se não existe empréstimo pendente, persiste:
            $em->persist($loan);
            $em->flush();

            return $this->redirectToRoute('loan_index');
        }

        return $this->render('Loan/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{id}/edit", name="loan_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction($id, Request $request){
        $em = $this->getDoctrine()->getManager(); // Variável que se conecta ao Doctrine e gerencia
        $loan = $em->getRepository(Loan::class)->find($id); // Entra na variavel $em, tem acesso ao repositorio e procura o ID do loan

        if (!$loan){
            throw $this->createNotFoundException("Emprestimo não encontrada"); // Cria um if para ver se encontra a categoria. Se não, dispara erro.
        }

        $form = $this->createForm(LoanType::class, $loan); // Cria um formulário, baseado no loanType.php
        $form->handleRequest($request); // Manda uma request

        if ($form->isSubmitted() && $form->isValid()){ // Se os dados forem validos
            $em->flush(); // Atualiza o banco de dados
            return $this->redirectToRoute('loan_index'); // E retorna ao index
        };

        return $this->render('Loan/edit.html.twig', ['form' => $form->createView(), 'loan' => $loan,]); // Renderiza no edit
    }

    /**
     * @Route("/{id}/show", name="loan_show")
     * @Method("GET")
     */
    public function showAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $loan = $em->getRepository(Loan::class)->find($id);
        $deleteForms = [];
        if (!$loan){
            throw $this->createNotFoundException('Emprestimo não encontrado.');
        }

        $deleteForm = $this->createDeleteForm($loan);

        return $this->render('Loan/show.html.twig', [
        'loan' => $loan,
        'delete_forms' => $deleteForm->createView(),]);// envia para a view
    }

    /**
     * @Route("/{id}/delete", name="loan_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Loan $loan){
        $form = $this->createDeleteForm($loan); // Cria o formulário de delete
        $form->handleRequest($request); // Envia a requisição para o banco de dados

        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($loan);
            $em->flush();
        }

        return $this->redirectToRoute("loan_index");
    }

    public function createDeleteForm(Loan $loan){
        return $this->createFormBuilder()
        ->setAction($this->generateUrl('loan_delete', ['id' => $loan->getId()]))
        ->setMethod("DELETE")
        ->getForm();
    }

    /**
     * @Route("/{id}/history", name="reader_history")
     * @Method("GET")
     */
    public function historyAction(Reader $reader)
    {
        $loans = $this->getDoctrine()
                    ->getRepository(Loan::class)
                    ->findBy(['reader' => $reader], ['loanDate' => 'DESC']);

        return $this->render('Reader/history.html.twig', [
            'reader' => $reader,
            'loans'  => $loans,
        ]);
    }
}
