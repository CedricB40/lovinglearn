<?php

namespace App\Form;

use App\Entity\Theme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThemeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
$builder
    ->add('name', TextType::class, [
        'label' => 'Nom du thème',
    ])
    ->add('description', TextareaType::class, [
        'label' => 'Description',
    ])
    ->add('image', TextType::class, [
        'label' => 'Image (emoji)',
        'required' => false,
    ])
    ->add('color', ColorType::class, [
        'label' => 'Couleur du thème',
    ])
    ->add('save', SubmitType::class, [
        'label' => 'Enregistrer',
        'attr' => ['class' => 'btn btn-primary rounded-3 fw-bold'],
    ])
;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Theme::class,
        ]);
    }
}