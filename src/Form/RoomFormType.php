<?php

namespace App\Form;

use App\Entity\Room;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class RoomFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('bedNbr')
            ->add('prix')
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Description de la chambre',
                    'rows' => 4,
                    'style' => 'resize: vertical;',
                    'maxlength' => 255,
                ],
            ])            ->add('thumbnail', FileType::class, [
                'label' => 'Thumbnail (JPEG or PNG)',
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'form-control-file'],
                'data_class' => null
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}
