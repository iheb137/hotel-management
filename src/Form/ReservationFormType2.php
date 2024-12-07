<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Reservation;
use App\Entity\Room;
use App\Entity\Service;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationFormType2 extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('StartDate', null, [
                'widget' => 'single_text',
            ])
            ->add('EndDate', null, [
                'widget' => 'single_text',
            ])


            ->add('room', EntityType::class, [
                'class' => Room::class,
                'choice_label' => 'name',
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
