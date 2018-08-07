<?php

namespace App\Form\Type;

use App\Entity\Purchase;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PurchaseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @SuppressWarnings("unused")
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'type',
                ChoiceType::class,
                [
                    'choices' => [
                      'game_purchase' => Purchase::GAME_PURCHASE,
                      'dlc_purchase' => Purchase::DLC_PURCHASE,
                      'ingame_purchase' => Purchase::INGAME_PURCHASE,
                      'other_purchase' => Purchase::OTHER_PURCHASE
                    ],
                    'translation_domain' => 'messages',
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
                    'translation_domain' => 'messages',
                    'choices' => [
                        'USD' => 'USD',
                        'EUR' => 'EUR'
                    ],
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ]
                ]
            )
            ->add(
                'notice',
                TextType::class,
                [
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ],
                    'required' => false
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
                    'required' => true,
                    'widget' => 'single_text',
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ]
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'submit',
                    'attr' => [
                        'class' => 'btn btn-success form-control col-md-2'
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Purchase::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'purchase'
        ]);
    }
}
