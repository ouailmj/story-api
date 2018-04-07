<?php

/**
 * Created by PhpStorm.
 * User: soufianeMIT
 * Date: 04/04/18
 * Time: 14:59
 */

namespace AppBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('username')
            ->remove('plainPassword')
            ->remove('email')

            ->add('plainPassword' , PasswordType::class,[
                'label' =>  false,
                'attr' => array('class' => 'input100','placeholder'=>'registration.placeholder.plainPassword'),
                ])

            ->add('username' , null,[
                'label' =>  false,
                'attr' => array('class' => 'input100','placeholder'=>'registration.placeholder.username'),
            ])

            ->add('email' , EmailType::class,[
                'label' =>  false,
                'attr' => array('class' => 'input100','placeholder'=>'registration.placeholder.email'),
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