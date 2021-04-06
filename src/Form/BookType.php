<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\PublishingHouse;
use App\Entity\Type;
use App\Entity\Writer;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
            ->add('description',TextareaType::class)
            ->add('publishedAt', DateType::class,[
                'label' => 'Date de publication'
            ])
            ->add('ISBN', TextType::class,[
                'label' => 'Numéro ISBN'
            ])
            ->add('types',EntityType::class, [
                'class' => Type::class,
                'multiple' => true, //on est en ManyToMany
                'expanded' => false,
                'choice_label' => 'name',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.name','ASC');
                },
                'by_reference' => false, //indique qu'il ne faut pas passer par les setter mais par les 'add' (manytomany)
                'attr' => [
                    'class' => 'select-types'
                ]
            ])
            ->add('publishingHouse',EntityType::class,[
                'label' => 'Editeur',
                'placeholder' => 'choisir un Editeur',
                'class' => PublishingHouse::class,
                'choice_label' => function(PublishingHouse $publishingHouse){
                    return strtoupper($publishingHouse->getName());
                },
                'required' =>false
            ])
            ->add('writers', EntityType::class, [
                'class' => Writer::class,
                'multiple' => true,
                'expanded' => false,
                'choice_label' =>'lastName',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('w')
                        ->orderBy('w.lastName','ASC');
                },
                'by_reference' => false,
                'attr' => [
                    'class' => 'select-writers'
                ]
            ])
            ->add('cover', FileType::class, [
                'label' => false,
                'multiple' => false, //ici on ne veut qu'un fichier
                'mapped' => false,
                'required' => false
            ])
            ->add('valider', SubmitType::class)
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
