<?php

namespace App\Form\User;

use App\Entity\User\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PerfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, [
                'required'=>true,
                'attr'=>[
                    'class'=>'form-control form-control-sm'
                ]
            ])

            ->add('nombre2', TextType::class,[
                'required'=>false,
                'attr'=>[
                    'class'=>'form-control form-control-sm'
                ]
            ])

            ->add('apellido', TextType::class,[
                'required'=>true,
                'attr'=>[
                    'class'=>'form-control form-control-sm'
                ]
            ])

            ->add('apellido2', TextType::class,[
                'required'=>false,
                'attr'=>[
                    'class'=>'form-control form-control-sm'
                ]
            ])

            ->add('telefono', TextType::class,[
                'required'=>false,
                'attr'=>[
                    'class'=>'form-control form-control-sm'
                ]
            ])

            ->add('email', EmailType::class,[
                'required'=>true,
                'attr'=>[
                    'class'=>'form-control form-control-sm'
                ]
            ])

            ->add('password', PasswordType::class,[
                'attr'=>[
                    'placeholder'=>'****',
                ],
                'required'=>true,
                'attr'=>[
                    'class'=>'form-control form-control-sm'
                ],
                'empty_data'=>'',
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
