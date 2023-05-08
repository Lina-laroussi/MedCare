<?php

namespace App\Form;

use App\Entity\Facture;
use App\Entity\FicheAssurance;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('Facture', EntityType :: class, [
                'class' => Facture::class,
                'choice_label' => 'id',
            ]);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FicheAssurance::class,
        ]);
    }
}
