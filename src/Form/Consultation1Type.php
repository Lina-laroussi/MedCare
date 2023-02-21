<?php

namespace App\Form;

use App\Entity\Consultation;
use App\Entity\RendezVous;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Consultation1Type extends AbstractType
{
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }
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
            ->add('pression_arterielle', NumberType::class)
            ->add('frequence_cardiaque', NumberType::class)
            ->add('taux_glycemie', NumberType::class)
            ->add('symptomes', TextType::class)
            ->add('traitement', TextType::class)
            ->add('observation', TextType::class)
            ->add('rendezVous', EntityType::class,[
                'class' => RendezVous::class,
                'choice_label' => 'date'
            ])
            ->add('fiche_medicale', ButtonType::class, [
                'label' => 'Ajouter fiche medicale'
                ])

            ->add('ordonnance', ButtonType::class, [
                'label' => 'Ajouter ordonnance'
                ]);
                $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                    $form = $event->getForm();
                    $data = $event->getData();
                    $clickedButtonName = '';
        
                    foreach ($form->all() as $name => $child) {
                        if ($child-> isValid()) {
                            $clickedButtonName = $name;
                            break;
                        }
                    }
                    if ($clickedButtonName === 'ordonnance') {
                        $url = $this->router->generate('Front-Office/ordonnance/new.html.twig', ['id' => $data->getId()]);
                        $response = new RedirectResponse($url);
                    }
                });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}
