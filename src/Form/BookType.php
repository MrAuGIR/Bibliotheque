<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\PublishingHouse;
use App\Entity\Writer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,[
                'label' => 'titre du livre',
                'attr' => ['class' => 'form-control']
            ])
            ->add('description')
            ->add('publishedAt', DateType::class,[
                'label' => 'Date de publication'
            ])
            ->add('ISBN', TextType::class,[
                'label' => 'Numéro ISBN'
            ])
            ->add('types')
            ->add('publishingHouse',EntityType::class,[
                'label' => 'Editeur',
                'placeholder' => 'choisir un Editeur',
                'class' => PublishingHouse::class,
                'choice_label' => function(PublishingHouse $publishingHouse){
                    return strtoupper($publishingHouse->getName());
                },
                'required' =>false
            ])
            ->add('writers', CollectionType::class, [
                'entry_type' => WriterType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
            ])
            ->add('pictures', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
