<?php

namespace App\Form;

use App\Entity\Image;
use App\Entity\Itinerary;
use App\Entity\Place;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'First Name',
                'attr' => ['placeholder' => 'Entrez votre prénom...'],
                'required' => true,
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Last Name',
                'attr' => ['placeholder' => 'Entrez votre nom...'],
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'Entrez votre email...'],
                'required' => true,
            ])
            ->add('phone', TextType::class, [
                'label' => 'Phone',
                'attr' => ['placeholder' => 'Facultatif...'],
                'required' => false,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Password', 'attr' => ['placeholder' => 'Entrez votre mot de passe...']],
                'second_options' => ['label' => 'Repeat Password', 'attr' => ['placeholder' => 'Confirmez votre mot de passe...']],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
