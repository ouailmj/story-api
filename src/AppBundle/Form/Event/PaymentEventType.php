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

namespace AppBundle\Form\Event;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentEventType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numberCard', TextType::class, [
                'label' => 'payment.fields.numberCard',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Card number',
                ],
            ])
            ->add('cvv', TextType::class, [
                'label' => 'payment.fields.cvv',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'CVV',
                ],
            ])
            ->add('expireAtMonth', TextType::class, [
                'label' => 'payment.fields.price',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Date expire (Month)',
                ],
            ])
            ->add('expireAtYear', TextType::class, [
                'label' => 'payment.fields.price',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Date expire (Year)',
                ],
            ])
            ->add('price', TextType::class, [
                'label' => 'payment.fields.price',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Prix',
                ],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Payment',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_payment';
    }
}
