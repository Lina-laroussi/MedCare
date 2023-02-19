<?php

namespace App\Form;

use App\Entity\Consultation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Consultation1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('poids', DateType::class, [
               
                'label' => 'Date Debut',
                'widget' => 'single_text',
                'format' => 'float',
            ])
            ->add('taille')
            ->add('imc')
            ->add('temperature')
            ->add('prix')
            ->add('pression_arterielle')
            ->add('frequence_cardiaque')
            ->add('taux_glycemie')
            ->add('symptomes')
            ->add('traitement')
            ->add('observation')
            ->add('rendezVous')
            ->add('fiche_medicale')
            ->add('ordonnance')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}
