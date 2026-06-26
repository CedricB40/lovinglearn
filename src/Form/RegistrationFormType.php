<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', options: [
                'constraints' => [
                    new NotBlank(message: 'registerForm.emailRequired'),
                ],
            ])
            ->add('firstname', options: [
                'constraints' => [
                    new NotBlank(message: 'registerForm.firstnameRequired'),
                    new Length(
                        min: 2,
                        minMessage: 'registerForm.firstnameMinLength',
                        max: 50,
                        maxMessage: 'registerForm.firstnameMaxLength',
                    ),
                ],
            ])
            ->add('lastname', options: [
                'constraints' => [
                    new NotBlank(message: 'registerForm.lastnameRequired'),
                    new Length(
                        min: 2,
                        minMessage: 'registerForm.lastnameMinLength',
                        max: 50,
                        maxMessage: 'registerForm.lastnameMaxLength',
                    ),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank(message: 'registerForm.passwordRequired'),
                    new Length(
                        min: 6,
                        minMessage: 'registerForm.passwordMinLength',
                        max: 4096,
                    ),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue(message: 'You should agree to our terms.'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}