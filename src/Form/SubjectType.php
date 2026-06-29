<?php

namespace App\Form;

use App\Entity\Subject;
use App\Entity\Theme;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du sujet',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description courte',
                'attr' => ['rows' => 3],
            ])
            ->add('content', CKEditorType::class, [
                'label' => 'Contenu détaillé',
            ])
            ->add('image', TextType::class, [
                'label' => 'Image (nom du fichier)',
                'required' => false,
            ])
            ->add('theme', EntityType::class, [
                'class' => Theme::class,
                'choice_label' => 'name',
                'label' => 'Thème',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Subject::class,
        ]);
    }
}