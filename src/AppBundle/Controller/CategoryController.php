<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;


/**
 * @Route("category")
 */
class CategoryController extends Controller
{
    /**
     * @Route("/", name="category_index")
     */
    public function indexAction()
    {
        $categorys = $this->getDoctrine()
                        ->getRepository(Category::class)
                        ->findAll();
        $deleteForms = [];
        foreach ($categorys as $category) {
            $deleteForms[$category->getId()] = $this->createDeleteForm($category)->createView();
        }

        return $this->render('category/index.html.twig', [
            'categorys' => $categorys,
            'delete_forms' => $deleteForms
        ]);
    }

    /**
     * @Route("/new", name="category_new")
     */
    public function newAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="category_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction($id, Request $request){
        $em = $this->getDoctrine()->getManager(); // Variável que se conecta ao Doctrine e gerencia
        $category = $em->getRepository(Category::class)->find($id); // Entra na variavel $em, tem acesso ao repositorio e procura o ID do category

        if (!$category){
            throw $this->createNotFoundException("Categoria não encontrada"); // Cria um if para ver se encontra a categoria. Se não, dispara erro.
        }

        $form = $this->createForm(CategoryType::class, $category); // Cria um formulário, baseado no categoryType.php
        $form->handleRequest($request); // Manda uma request

        if ($form->isSubmitted() && $form->isValid()){ // Se os dados forem validos
            $em->flush(); // Atualiza o banco de dados
            return $this->redirectToRoute('category_index'); // E retorna ao index
        };

        return $this->render('category/edit.html.twig', ['form' => $form->createView(), 'category' => $category,]); // Renderiza no edit
    }

    /**
     * @Route("/{id}/show", name="category_show")
     * @Method("GET")
     */
    public function showAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository(Category::class)->find($id);
        $deleteForms = [];
        if (!$category){
            throw $this->createNotFoundException('Autor não encontrado.');
        }

        $deleteForm = $this->createDeleteForm($category);

        return $this->render('category/show.html.twig', [
        'category' => $category,
        'delete_forms' => $deleteForm->createView(),]);// envia para a view
    }

    /**
     * @Route("/{id}/delete", name="category_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Category $category){
        $form = $this->createDeleteForm($category); // Cria o formulário de delete
        $form->handleRequest($request); // Envia a requisição para o banco de dados

        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
        }

        return $this->redirectToRoute("category_index");
    }

    public function createDeleteForm(Category $category){
        return $this->createFormBuilder()
        ->setAction($this->generateUrl('category_delete', ['id' => $category->getId()]))
        ->setMethod("DELETE")
        ->getForm();
    }
}
