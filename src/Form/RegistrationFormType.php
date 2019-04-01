<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RegistrationFormType
 */
class RegistrationFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('POST')
            ->add(
                'email',
                EmailType::class,
                [
                    'attr' =>
                        [
                            'class' => 'form-control',
                            'placeholder' => 'email',
                        ],
                ]
            )
            ->add(
                'password',
                PasswordType::class,
                [
                    'attr' =>
                        [
                            'class' => 'form-control',
                            'placeholder' => 'password',
                        ],
                ]
            )
            ->add(
                'inviteCode',
                TextType::class,
                [
                    'attr' =>
                        [
                            'class' => 'form-control mb-3',
                            'placeholder' => 'invite_code',
                        ],
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'attr' => [
                        'class' => 'form-control btn btn-primary',
                    ],
                    'label' => 'create_account',
                ]
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
