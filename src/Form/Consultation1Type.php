<?php

namespace App\Form;

use App\Entity\Consultation;
use App\Entity\FicheMedicale;
use App\Entity\Ordonnance;
use App\Entity\RendezVous;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Consultation1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('poids', NumberType::class)
            ->add('taille', NumberType::class)
            ->add('imc', NumberType::class)
            ->add('temperature', NumberType::class)
            ->add('prix', MoneyType::class, array(
                'scale' => 2, 
                'currency' => 'TND'
            ))
            ->add('pression_arterielle', NumberType::class, [
                'required' => false,
            ])
            ->add('frequence_cardiaque', NumberType::class, [
                'required' => false,
            ])
            ->add('taux_glycemie', NumberType::class, [
                'required' => false,
            ])
            ->add('symptomes', TextType::class)
            ->add('traitement', TextType::class)
            ->add('observation', TextType::class)
            ->add('rendezVous', EntityType::class,[
                'class' => RendezVous::class,
                'choice_label' => 'date'
            ])
            ->add('fiche_medicale', EntityType::class,[
                'class' => FicheMedicale::class,
                'choice_label' => 'description'
                ])

            ->add('ordonnance', EntityType::class,[
                'class' => Ordonnance::class,
                'choice_label' => 'code_ordonnance'
                ]);
                
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}
