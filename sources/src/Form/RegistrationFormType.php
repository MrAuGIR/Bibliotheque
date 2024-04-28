<?php

namespace App\Form;

use App\Entity\Biblio;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Votre email',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('password', PasswordType::class, [
                "mapped" => false,
                "constraints" => [
                    new NotBlank([
                        'message' => "Votre mot de passe est obligatoire",
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => "Votre mot de passe doit avoir au moins {{ limit }} caractere",
                    ])
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => "J'accepte les termes",
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => "Veuillez accepter les termes",
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
