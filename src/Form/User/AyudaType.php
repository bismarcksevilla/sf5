<?php
namespace App\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AyudaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('email', EmailType::class,[
                'label'         => 'Correo Electrónico',
                'required'      => true,
                'attr'          => [
                    'class'=>'',
                    'placeholder' => 'mail@dominio.com'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'El campo no puede estar vacío.']),
                    new Email(['message' => 'Email inválido.']),
                    new Length([
                        'min' => 5,
                        'minMessage'=>'Escriba 5 caracteres como minimo',
                        'max' => 50,
                        'maxMessage'=>'No escriba más de 50 caracteres'
                    ]),

                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
