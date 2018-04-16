<?php

/*
 * This file is part of the Instan't App project.
 *
 * (c) Instan't App <contact@instant-app.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Developed by MIT <contact@mit-agency.com>
 *
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $constraintsOptions = [
            'message' => 'fos_user.current_password.invalid',
        ];

        $builder

            ->remove('current_password')
            ->remove('plainPassword')

            ->add('current_password', PasswordType::class, [
                  'label' => false,
                  'translation_domain' => 'FOSUserBundle',
                  'mapped' => false,
                  'constraints' => [
                      new NotBlank(),
                      new UserPassword($constraintsOptions),
                  ],
                  'attr' => [
                      'autocomplete' => 'current-password',
                      'class' => 'input100',
                      'placeholder' => 'form.current_password',
                  ],
              ])

        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'options' => [
                'translation_domain' => 'FOSUserBundle',
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'input100',
                ],
            ],
            'first_options' => [
                'label' => false,
                'attr' => [
                    'placeholder' => 'form.new_password',
                ],
            ],
            'second_options' => [
                'label' => false,
                'attr' => [
                    'placeholder' => 'form.new_password_confirmation',
                ],
                ],
            'invalid_message' => 'fos_user.password.mismatch',
        ]);
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ChangePasswordFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_change_password';
    }
}
