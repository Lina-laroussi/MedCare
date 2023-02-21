<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class EditFormMedecinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class)

            ->add('prenom',TextType::class)

            ->add('date_de_naissance',DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'constraints' => [new notBlank(['message'=>('Veuillez renseigner votre date de naissance')])]
            ])
            ->add('email',TextType::class)
            ->add('num_tel',TextType::class,[
                'constraints' => [new notBlank(['message'=>('Veuillez renseigner votre numéro de téléphone')]),
                    new Regex(pattern:"/^[0-9]*$/", message:"Votre numéro de téléphone n'est pas valide")
                    ]
            ])
            ->add('sexe',ChoiceType::class, [
                'choices'  => [
                    'Femme' => "femme",
                    'Homme' =>"Homme"
                ]],
            )
            ->add('description',TextareaType::class,[
                'constraints' => [new notBlank(['message'=>('Veuillez renseigner votre description')]),
                    new Length(
                        min:8,
                        max: 1000,
                        minMessage: 'Votre description doit comporter au moins {{ limit }} caractères',
                        maxMessage: 'Votre description ne peut pas dépasser {{ limit }} caractères',
                    )
                    ]
            ])
            ->add('adresse',TextType::class,
                ['constraints' => [new notBlank(['message'=>('Veuillez renseigner votre adresse')]),
                    new Length(
                        min:3,
                        max: 30,
                        minMessage: 'Votre adresse doit comporter au moins {{ limit }} caractères',
                        maxMessage: 'Votre adresse ne peut pas dépasser {{ limit }} caractères',
                    )
                    ]])

            ->add('specialite',TextType::class,[
                'constraints' => [new notBlank(['message'=>('Veuillez renseigner votre spécialité')]),
                    new Length(
                        min:3,
                        max: 30,
                        minMessage: 'Votre spécialité doit comporter au moins {{ limit }} caractères',
                        maxMessage: 'Votre spécialité ne peut pas dépasser {{ limit }} caractères',
                    )
                    ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
