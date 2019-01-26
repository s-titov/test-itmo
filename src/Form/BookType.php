<?php

namespace App\Form;

use App\Entity\{Book, Author};
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType, IntegerType, SubmitType};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Название', 'attr' => ['maxlength' => 190, 'autocomplete' => 'off']
            ])
            ->add('year', IntegerType::class, [
                'label' => 'Год', 'attr' => ['max' => date('Y')]
            ])
            ->add('isbn', TextType::class, [
                'label' => 'ISBN', 'attr' => ['maxlength' => 190, 'autocomplete' => 'off']
            ])
            ->add('pages_count', IntegerType::class, [
                'label' => 'Количество страниц', 'required' => false
            ])
            ->add('authors', EntityType::class, [
                'label' => 'Авторы', 'class' => Author::class,
                'choice_label' => 'name', 'multiple' => true,
                'required' => false
            ])
            ->add('submit', SubmitType::class, ['label' => 'Отправить'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
