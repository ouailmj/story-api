<?php

/*
 * This file is part of the Instan't App project.
 *
 * (c) Instan't App <contact@instant-app.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $constraintsOptions = array(
            'message' => 'fos_user.current_password.invalid',
        );

        $builder

            ->remove('username')
            ->remove('email')
            ->remove('current_password')

            ->add('username', TextType::class,  [
                'label' => false,
                'attr' => ['class' => 'input100', 'placeholder' => 'registration.placeholder.username'],
            ])

            ->add('email', EmailType::class,  [
                'label' => false,
                'attr' => ['class' => 'input100','placeholder' => 'registration.placeholder.email'],
            ])

            ->add('phoneNumber', TelType::class,  [
                'label' => false,
                'attr' => ['class' => 'input100','placeholder' => 'user.fields.phone_number'],
            ])

            ->add('fullName', TextType::class,  [
                'label' => false,
                'attr' => ['class' => 'input100','placeholder' => 'user.fields.full_name'],
            ])

            ->add('timezoneId', TimezoneType::class,  [
                'label' => false,
                'attr' => ['class' => 'input100','placeholder' => 'user.fields.time_zone'],
            ])

            ->add('current_password', PasswordType::class,  [
                'label' => false,
                'attr' => [
                    'class' => 'input100',
                    'placeholder' => 'user.fields.password',
                ],
                'constraints' => array(
                    new NotBlank(),
                    new UserPassword($constraintsOptions),
                ),
                'mapped' => false,

            ])

        ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_profile';
    }
}
