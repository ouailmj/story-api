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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $permissions = [
            'user.fields.user' => 'ROLE_USER',
            'user.fields.admin' => 'ROLE_ADMIN',
        ];

        $builder
            ->add('fullName', TextType::class, [
                'label' => 'user.fields.full_name',
            ])

            ->add('username', TextType::class, [
                'label' => 'user.fields.username',
            ])

            ->add('email', EmailType::class, [
                'label' => 'user.fields.email',
            ])

            ->add('new_password', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'user.new.password_invalid',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => ['label' => 'user.fields.password'],
                'second_options' => ['label' => 'user.fields.password_confirmation'],
            ])

            ->add('phoneNumber', TelType::class, [
                'label' => 'user.fields.phone_number',
            ])

            ->add('timezoneId', TimezoneType::class, [
                'label' => 'user.fields.time_zone',
                'attr' => ['class'=> 'select-search'],
            ])

            ->add('role', ChoiceType::class, [
                'label' => 'user.fields.role',
                'attr' => ['class'=> 'select-search'],
                'choices' => $permissions,
                'multiple' => false,
                'expanded' => false,
                'mapped' => false,
            ])
            ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\User',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }
}
