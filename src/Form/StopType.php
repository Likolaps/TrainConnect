<?php

namespace App\Form;

use App\Entity\Line;
use App\Entity\Station;
use App\Entity\Stop;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_time_departure', null, [
                'widget' => 'single_text',
            ])
            ->add('date_time_arrival', null, [
                'widget' => 'single_text',
            ])
            ->add('line', EntityType::class, [
                'class' => Line::class,
                'choice_label' => 'id',
            ])
            ->add('station', EntityType::class, [
                'class' => Station::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stop::class,
        ]);
    }
}
