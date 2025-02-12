<?php

namespace App\Form;

use App\Entity\Accessibility;
use App\Entity\Category;
use App\Entity\Place;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'empty_data' => '',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
            ])
            ->add('latitude', NumberType::class, [
                'scale' => 6,
                'required' => true,
            ])
            ->add('longitude', NumberType::class, [
                'scale' => 6,
                'required' => true,
            ])
            ->add('PostalCode', TextType::class, [
                'required' => true,
                'attr' => ['maxlength' => 10],
            ])
            ->add('city', TextType::class, [
                'required' => true,
            ])
            ->add('address', TextType::class, [
                'required' => true,
            ])
            ->add('priceMin', MoneyType::class, [
                'currency' => 'EUR',
                'required' => false,
            ])
            ->add('priceMax', MoneyType::class, [
                'currency' => 'EUR',
                'required' => false,
            ])
            ->add('visitTime', IntegerType::class, [
                'required' => false,
                'attr' => ['min' => 0],
            ])
            ->add('note', RangeType::class, [
                'attr' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.1,
                ],
                'required' => false,
            ])
            ->add('accessibility', EntityType::class, [
                'class' => Accessibility::class,
                'choice_label' => 'name',
                'multiple' => true,
                'query_builder' => function (EntityRepository $entityRepository) {
                    return $entityRepository->createQueryBuilder('a')
                        ->orderBy('a.name', 'ASC');
                },
                'required' => false,
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $entityRepository) {
                    return $entityRepository->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Place::class,
        ]);
    }
}
