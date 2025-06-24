<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Reader;
use AppBundle\Form\ReaderType;
use AppBundle\Entity\Book;
use AppBundle\Form\BookType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("reader")
 */
class ReaderController extends Controller
{
    /**
     * @Route("/", name="reader_index")
     */
    public function indexAction(Request $request)
        {// 1. captura o único parâmetro
        $q = trim($request->query->get('q'));

        // 2. monta o QueryBuilder
        $qb = $this->getDoctrine()
                   ->getRepository(Reader::class)
                   ->createQueryBuilder('r');

        if ($q !== '') {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('r.name',  ':q'),
                    $qb->expr()->like('r.email', ':q'),
                    $qb->expr()->like('r.phone', ':q'),
                    $qb->expr()->like('r.cpf', ':q')
                )
            )
            ->setParameter('q', '%'.$q.'%');
        }

        // 3. executa e ordena
        $readers = $qb
            ->orderBy('r.name','ASC')
            ->getQuery()
            ->getResult();

        $deleteForms = [];
        foreach ($readers as $reader) {
            $deleteForms[$reader->getId()] = $this->createDeleteForm($reader)->createView();
        }

        return $this->render('Reader/index.html.twig', [
            'readers' => $readers,
            'delete_forms' => $deleteForms,
            'search' => ['q' => $q],
        ]);
    }

    /**
     * @Route("/new", name="reader_new")
     */
    public function newAction(Request $request)
    {
        $reader = new Reader();
        $form = $this->createForm(ReaderType::class, $reader);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reader);
            $em->flush();

            return $this->redirectToRoute('reader_index');
        }

        return $this->render('Reader/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="reader_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction($id, Request $request){
        $em = $this->getDoctrine()->getManager(); // Variável que se conecta ao Doctrine e gerencia
        $reader = $em->getRepository(Reader::class)->find($id); // Entra na variavel $em, tem acesso ao repositorio e procura o ID do reader

        if (!$reader){
            throw $this->createNotFoundException("Leitor não encontrado"); // Cria um if para ver se encontra o autor. Se não, dispara erro.
        }

        $form = $this->createForm(ReaderType::class, $reader); // Cria um formulário, baseado no readerType.php
        $form->handleRequest($request); // Manda uma request

        if ($form->isSubmitted() && $form->isValid()){ // Se os dados forem validos
            $em->flush(); // Atualiza o banco de dados
            return $this->redirectToRoute('reader_index'); // E retorna ao index
        };

        return $this->render('Reader/edit.html.twig', ['form' => $form->createView(), 'reader' => $reader,]); // Renderiza no edit
    }

    /**
     * @Route("/{id}/show", name="reader_show")
     * @Method("GET")
     */
    public function showAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $reader = $em->getRepository(Reader::class)->find($id);
        $deleteForms = [];
        if (!$reader){
            throw $this->createNotFoundException('Leitor não encontrado.');
        }

        $deleteForm = $this->createDeleteForm($reader);

        return $this->render('Reader/show.html.twig', [
        'reader' => $reader,
        'delete_forms' => $deleteForm->createView(),]);// envia para a view
    }

    /**
     * @Route("/{id}/delete", name="reader_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Reader $reader){
        $form = $this->createDeleteForm($reader); // Cria o formulário de delete
        $form->handleRequest($request); // Envia a requisição para o banco de dados

        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($reader);
            $em->flush();
        }

        return $this->redirectToRoute("reader_index");
    }

    public function createDeleteForm(Reader $reader){
        return $this->createFormBuilder()
        ->setAction($this->generateUrl('reader_delete', ['id' => $reader->getId()]))
        ->setMethod("DELETE")
        ->getForm();
    }
}
