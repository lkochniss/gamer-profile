<?php

namespace App\Form\Type;

use App\Entity\Purchase;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PurchaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'type',
                ChoiceType::class,
                [
                    'choices' => [
                      'Game Purchase' => 'game-purchase',
                      'DLC Purchase' => 'dlc-purchase',
                      'Ingame Purchase' => 'ingame-purchase',
                      'Other Purchase' => 'other-purchase'
                    ],
                    'translation_domain' => 'purchase',
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ]
                ]
            )
            ->add(
                'price',
                NumberType::class,
                [
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ]
                ]
            )
            ->add(
                'currency',
                ChoiceType::class,
                [
                    'choices' => [
                        '$' => 'USD',
                        '€' => 'EUR'
                    ],
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ]
                ]
            )
            ->add(
                'notice',
                TextareaType::class,
                [
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ]
                ]
            )
            ->add(
                'game',
                EntityType::class,
                [
                    'class' => 'App\Entity\Game',
                    'choice_label' => 'name',
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ]
                ]
            )
            ->add(
                'boughtAt',
                DateType::class,
                [
                    'years' => range(2000, 2050),
                    'format' => 'dMy',
                    'attr' => [
                        'class' => 'form-controll'
                    ]
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'attr' => [
                        'class' => 'btn-success form-controll'
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Purchase::class,
        ]);
    }
}