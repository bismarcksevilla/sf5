<?php
namespace App\Form\User;

use App\Entity\User\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
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

            ->add('role', ChoiceType::class,[
                'choices' => [
                    'USUARIO: Acceso limitado'          => 'ROLE_USER',
                    'CONTABILIDAD: Acceso limitado'          => 'ROLE_CONTABILIDAD',
                    'TESORERIA: Acceso limitado'          => 'ROLE_TESORERIA',
                    'AUXILIAR CONTABLE: Acceso limitado'          => 'ROLE_AUXILIAR',
                    'COLABORADOR: Acceso al sistema'    => 'ROLE_COLABORADOR',
                    'ADMIN: Administrador'              => 'ROLE_ADMIN',
                ],
                'attr'=>[
                    'class'=>'form-control form-control-sm'
                ]
            ])

            ->add('estado', ChoiceType::class,[
                'choices' => [
                    'ACTIVO'          => 'ACTIVO',
                    'INACTIVO/BLOQUEADO'    => 'INACTIVO',
                ],
                'attr'=>[
                    'class'=>'form-control form-control-sm'
                ]
            ])

            // ->add('compania', EntityType::class,[
            //     'class' => Compania::class,
            //     'attr'=>[
            //         'class'=>'form-control form-control-sm'
            //     ],
            //     'choice_label'  => function($Compania, $key, $index) {
            //         return  mb_strtoupper(  $Compania->getNombre() );
            //     },
            //     'query_builder' => function(CompaniaRepository $c){
            //         return $c->getChoices();
            //     },
            //     'placeholder'=> 'Seleccion un Compania',
            //     'required'=>false
            // ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
