<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Author;
use AppBundle\Entity\Category;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BookType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title')
        ->add('isbn')
        ->add('ano')
        ->add('category', EntityType::class,[
            'class' => Category::class,
            'choice_label' => 'title',
            'placeholder' => 'Selecione a categoria',
        ])
        ->add('author', EntityType::class,[
            'class' => Author::class,
            'choice_label' => 'name',
            'placeholder' => 'Selecione o autor',
        ])
        ->add('save', SubmitType::class,[
            'label' => 'Salvar livro'
        ])
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Book'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_book';
    }


}
