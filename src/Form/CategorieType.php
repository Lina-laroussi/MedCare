<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom' , ChoiceType::class, [
                'choices'  => [
                    'Santé' => 'Santé',
                    'Hygiène' => 'Hygiène',
                    'Visage' => 'Visage',
                    'Homme' => 'Homme',
                    'Corps' => 'Corps',
                    'Bébé & Maman' => 'Bébé_&_Maman',
                    'Sport ' => 'Sport ',
                ],
            ])
            ->add('description')
            

            ->add('etat', ChoiceType::class, [
                'choices'  => [
                    'Disponible' => 'disponible',
                    'Non disponible' => 'non_disponible',
                ],
            ])
            ->add('marque')
            ->add('groupe_age', ChoiceType::class, [
                'choices'  => [
                    'enfant' => '0 _ 14 ans ',
                    'adulte' => '15ans _ 50ans ',
                    'personne agée' => '+ 60 ans  '  ,
                ],
            ])
            ->add('admin')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
