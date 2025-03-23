<?php

namespace App\Form;

use App\Entity\Film;
use App\Entity\Salle;
use App\Entity\Seance;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class SeanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut', DateTimeType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'label' => 'Date de début',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('dateFin', DateTimeType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'label' => 'Date de fin',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('salle', EntityType::class, [
                'class' => Salle::class,
                'choice_label' => function (Salle $salle) {
                    return $salle->getCinema()->getVille() . ' - Salle ' . $salle->getNumSalle();
                },
                'attr' => ['class' => 'form-select'],
                'label' => 'Salle',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('film', EntityType::class, [
                'class' => Film::class,
                'choice_label' => 'titre',
                'attr' => ['class' => 'form-select'],
                'label' => 'Film',
                'label_attr' => ['class' => 'form-label'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Seance::class,
        ]);
    }
}