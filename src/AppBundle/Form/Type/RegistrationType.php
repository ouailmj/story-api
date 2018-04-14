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
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('username')
            ->remove('plainPassword')
            ->remove('email')

            ->add('plainPassword', PasswordType::class, [
                'label' => false,
                'attr' => ['class' => 'input100', 'placeholder' => 'registration.placeholder.plainPassword'],
                ])

            ->add('username', null, [
                'label' => false,
                'attr' => ['class' => 'input100', 'placeholder' => 'registration.placeholder.username'],
            ])

            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => ['class' => 'input100', 'placeholder' => 'registration.placeholder.email'],
            ])

        ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }
}
