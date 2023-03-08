<?php

namespace App\Form;

use App\Entity\FicheAssurance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FicheAssuranceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('num_adherent')
            ->add('description')
            ->add('date_creation')
            ->add('image_facture')
            ->add('etat', ChoiceType::class, [
                'choices'  => [
                    'Non confirmé' => 'Non confirmé',
                    'confirmée' => "confirmée",
                   
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FicheAssurance::class,
        ]);
    }
}
