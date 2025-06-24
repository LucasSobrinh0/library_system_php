<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Book;
use AppBundle\Entity\Reader;
use AppBundle\Entity\Loan;
use AppBundle\Form\LoanType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class LoanType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('returnDate', DateType::class, [
        'widget'   => 'single_text',
        'html5'    => true,
        'format'   => 'yyyy-MM-dd',
        'required' => false,
    ])
        ->add('loanDate', DateType::class, [
        'widget' => 'single_text',
        'html5'  => true,
        'format' => 'yyyy-MM-dd',
    ])
        ->add('book', EntityType::class,[
            'class' => Book::class,
            'choice_label' => 'title',
            'placeholder' => 'Selecionar Livro'
        ])
        ->add('reader', EntityType::class,[
            'class' => Reader::class,
            'choice_label' => 'name',
            'placeholder' => 'Selecionar Leitor'
        ])
        ->add('status', ChoiceType::class, [
                'label'       => 'Status do Empréstimo',
                'choices'     => [
                    'NOT_RETURNED' => 'NOT_RETURNED',
                    'RETURNED'     => 'RETURNED',
                ],
                // define o valor padrão
                'data'        => 'NOT_RETURNED',
                'expanded'    => false,
                'multiple'    => false,
            ])
        ->add('save', SubmitType::class,[
            'label' => 'Salvar'
        ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Loan'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_loan';
    }


}
