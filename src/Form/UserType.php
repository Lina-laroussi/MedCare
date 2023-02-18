<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Mime\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('password',RepeatedType::class,array(
                'type'=>PasswordType::class,
                'first_options'=> array('label'=>'password'),
                'second_options'=> array('label'=> 'Confirm password',
                    'constraints' => [new notBlank(['message'=>('Veuillez renseigner votre confirmation de mot de passe')])],
                ),
                'invalid_message' => 'Les mots de passe que vous avez entrés ne sont pas identiques.',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
