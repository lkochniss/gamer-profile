<?php

namespace App\Form\Type;

use App\Entity\BlogPost;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class
            )
            ->add(
                'content',
                TextareaType::class
            )
            ->add(
                'game',
                EntityType::class,
                array(
                    'class' => 'App\Entity\Game',
                    'choice_label' => 'name'
                )
            )
            ->add('submit',
                SubmitType::class,
                [
                    'attr' => [
                        'class' => 'btn-success'
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BlogPost::class,
        ]);
    }
}
