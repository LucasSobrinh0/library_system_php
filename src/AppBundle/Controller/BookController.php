<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Author;
use AppBundle\Form\AuthorType;
use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use AppBundle\Entity\Book;
use AppBundle\Form\BookType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("book")
 */
class BookController extends Controller
{
    /**
     * @Route("/", name="book_index")
     */
    public function indexAction()
    {
        $books = $this->getDoctrine()
                        ->getRepository(Book::class)
                        ->findAll();
        $deleteForms = [];
        foreach ($books as $book) {
            $deleteForms[$book->getId()] = $this->createDeleteForm($book)->createView();
        }

        return $this->render('Book/index.html.twig', [
            'books' => $books,
            'delete_forms' => $deleteForms
        ]);
    }

    /**
     * @Route("/new", name="book_new")
     */
    public function newAction(Request $request)
    {
        $book = new Book();
        $form = $this->createForm(bookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('book_index');
        }

        return $this->render('Book/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="book_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction($id, Request $request){
        $em = $this->getDoctrine()->getManager(); // Variável que se conecta ao Doctrine e gerencia
        $book = $em->getRepository(Book::class)->find($id); // Entra na variavel $em, tem acesso ao repositorio e procura o ID do book

        if (!$book){
            throw $this->createNotFoundException("Autor não encontrado"); // Cria um if para ver se encontra o autor. Se não, dispara erro.
        }

        $form = $this->createForm(BookType::class, $book); // Cria um formulário, baseado no bookType.php
        $form->handleRequest($request); // Manda uma request

        if ($form->isSubmitted() && $form->isValid()){ // Se os dados forem validos
            $em->flush(); // Atualiza o banco de dados
            return $this->redirectToRoute('book_index'); // E retorna ao index
        };

        return $this->render('Book/edit.html.twig', ['form' => $form->createView(), 'book' => $book,]); // Renderiza no edit
    }

    /**
     * @Route("/{id}/show", name="book_show")
     * @Method("GET")
     */
    public function showAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository(Book::class)->find($id);
        $deleteForms = [];
        if (!$book){
            throw $this->createNotFoundException('Autor não encontrado.');
        }

        $deleteForm = $this->createDeleteForm($book);

        return $this->render('Book/show.html.twig', [
        'book' => $book,
        'delete_forms' => $deleteForm->createView(),]);// envia para a view
    }

    /**
     * @Route("/{id}/delete", name="book_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, book $book){
        $form = $this->createDeleteForm($book); // Cria o formulário de delete
        $form->handleRequest($request); // Envia a requisição para o banco de dados

        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($book);
            $em->flush();
        }

        return $this->redirectToRoute("book_index");
    }

    public function createDeleteForm(book $book){
        return $this->createFormBuilder()
        ->setAction($this->generateUrl('book_delete', ['id' => $book->getId()]))
        ->setMethod("DELETE")
        ->getForm();
    }
}
