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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventCoverType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('coverType', ChoiceType::class, [
                'label' => 'event.fields.title',
                'choices' => [
                    'Video' => 'video',
                    'Imgae' => 'image',
                ],
                'multiple' => false,
                'expanded' => true,
                'mapped' => false,
            ])
            ->add('firstImageCover', FileType::class, [
                'label' => 'event.fields.firstImageCover',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'file-input',
                ],
            ])
            ->add('secondImageCover', FileType::class, [
                'label' => 'user.fields.secondImageCover',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'file-input',
                ],
            ])
            ->add('thirdImageCover', FileType::class, [
                'label' => 'user.fields.thirdImageCover',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'file-input',
                ],
            ])
            ->add('videoCover', FileType::class, [
                'label' => 'user.fields.videoCover',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'file-input',
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
            'data_class' => 'AppBundle\Entity\Event',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_event';
    }

}