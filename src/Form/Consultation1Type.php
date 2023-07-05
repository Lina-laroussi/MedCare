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
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Consultation1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('poids', NumberType::class, [
                'attr' => ['class' => 'form-control form-group']
            ])
            ->add('taille', NumberType::class, [
                'attr' => ['class' => 'form-control form-group']
            ])
            ->add('imc', NumberType::class, [
                'attr' => ['class' => 'form-control form-group']
            ])
            ->add('temperature', NumberType::class, [
                'attr' => ['class' => 'form-control form-group']
            ])
            ->add('prix', MoneyType::class, array(
                'scale' => 2,
                'currency' => 'TND',
                'attr' => ['class' => 'form-control form-group'
                ],
            ))
            ->add('pression_arterielle', NumberType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control form-group']
            ])
            ->add('frequence_cardiaque', NumberType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control form-group']
            ])
            ->add('taux_glycemie', NumberType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control form-group']
            ])
            ->add('maladie', TextType::class, [
                'attr' => ['class' => 'form-control form-group']
            ])
            ->add('traitement', TextType::class, [
                'attr' => ['class' => 'form-control form-group']
            ])
            ->add('observation', TextType::class, [
                'attr' => ['class' => 'form-control form-group']
            ])
            ->add('rendezVous', EntityType::class,[
                'class' => RendezVous::class,
                'choice_label' => function (RendezVous $rendezVous) {
                    $date = $rendezVous->getDate()->format('Y-m-d');
                    $heureDebut = $rendezVous->getHeureDebut()->format('H:i:s');
                    $heureFin = $rendezVous->getHeureFin()->format('H:i:s');
                    return "$date $heureDebut-$heureFin";
                },
                'attr' => ['class' => 'form-control form-group']
            ])            
            ->add('fiche_medicale', EntityType::class,[
                'class' => FicheMedicale::class,
                'choice_label' => 'Description',
                'attr' => ['class' => 'form-control form-group']
                ])
            /*    
            
            ->add('ordonnance', EntityType::class,[
                'class' => Ordonnance::class,
                'choice_label' => 'medicaments',
                'attr' => ['class' => 'form-control form-group']
                ])
            ->add('date_consultation', DateType::class,[
                'widget' => 'single_text',
                'html5' => false,
                'data' => new \DateTime(),
                'format' => 'dd/MM/yyyy H:i:s',
                'attr' => ['class' => 'form-control form-group']
            ])           
                */
            ;
                
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}
