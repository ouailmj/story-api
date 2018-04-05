<?php

/**
 * Created by PhpStorm.
 * User: soufianeMIT
 * Date: 04/04/18
 * Time: 14:59
 */

namespace AppBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phoneNumber', TelType::class, [
                'label' =>  'registration.fields.phone_number',
            ])

            ->add('fullName', TextType::class, [
                'label' =>  'registration.fields.full_name',
            ])

            ->add('timezoneId', TimezoneType::class, [
                'label' => 'registration.fields.time_zone',
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