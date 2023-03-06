<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class, [
                'label' => 'Start Date',
                'widget' => 'single_text',
            ])
            ->add('endDate', DateType::class, [
                'label' => 'End Date',
                'widget' => 'single_text',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FileSearch::class,
        ]);
    }
}
?>