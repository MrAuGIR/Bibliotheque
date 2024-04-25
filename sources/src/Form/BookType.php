<?php

namespace App\Form;

use App\Entity\Biblio;
use App\Entity\Book;
use App\Entity\PublishingHouse;
use App\Entity\Writer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('publishedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('ISBN')
            ->add('addedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('apiId')
            ->add('writers', EntityType::class, [
                'class' => Writer::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('biblios', EntityType::class, [
                'class' => Biblio::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('publishingHouse', EntityType::class, [
                'class' => PublishingHouse::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
