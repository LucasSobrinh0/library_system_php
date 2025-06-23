<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Author;
use AppBundle\Form\AuthorType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("author")
 */
class AuthorController extends Controller
{
    /**
     * @Route("/", name="author_index")
     */
    public function indexAction()
    {
        $authors = $this->getDoctrine()
                        ->getRepository(Author::class)
                        ->findAll();
        $deleteForms = [];
        foreach ($authors as $author) {
            $deleteForms[$author->getId()] = $this->createDeleteForm($author)->createView();
        }

        return $this->render('Author/index.html.twig', [
            'authors' => $authors,
            'delete_forms' => $deleteForms
        ]);
    }

    /**
     * @Route("/new", name="author_new")
     */
    public function newAction(Request $request)
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute('author_index');
        }

        return $this->render('Author/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="author_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction($id, Request $request){
        $em = $this->getDoctrine()->getManager(); // Variável que se conecta ao Doctrine e gerencia
        $author = $em->getRepository(Author::class)->find($id); // Entra na variavel $em, tem acesso ao repositorio e procura o ID do author

        if (!$author){
            throw $this->createNotFoundException("Autor não encontrado"); // Cria um if para ver se encontra o autor. Se não, dispara erro.
        }

        $form = $this->createForm(AuthorType::class, $author); // Cria um formulário, baseado no AuthorType.php
        $form->handleRequest($request); // Manda uma request

        if ($form->isSubmitted() && $form->isValid()){ // Se os dados forem validos
            $em->flush(); // Atualiza o banco de dados
            return $this->redirectToRoute('author_index'); // E retorna ao index
        };

        return $this->render('Author/edit.html.twig', ['form' => $form->createView(), 'author' => $author,]); // Renderiza no edit
    }

    /**
     * @Route("/{id}/show", name="author_show")
     * @Method("GET")
     */
    public function showAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $author = $em->getRepository(Author::class)->find($id);
        $deleteForms = [];
        if (!$author){
            throw $this->createNotFoundException('Autor não encontrado.');
        }

        $deleteForm = $this->createDeleteForm($author);

        return $this->render('Author/show.html.twig', [
        'author' => $author,
        'delete_forms' => $deleteForm->createView(),]);// envia para a view
    }

    /**
     * @Route("/{id}/delete", name="author_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Author $author){
        $form = $this->createDeleteForm($author); // Cria o formulário de delete
        $form->handleRequest($request); // Envia a requisição para o banco de dados

        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($author);
            $em->flush();
        }

        return $this->redirectToRoute("author_index");
    }

    public function createDeleteForm(Author $author){
        return $this->createFormBuilder()
        ->setAction($this->generateUrl('author_delete', ['id' => $author->getId()]))
        ->setMethod("DELETE")
        ->getForm();
    }
}
