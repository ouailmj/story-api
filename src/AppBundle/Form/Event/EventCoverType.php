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
use Symfony\Component\Form\Extension\Core\Type\UrlType;
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
                'label' => false,
                'choices' => [
                    'Video' => 'video',
                    'Image' => 'image',
                ],
                'data' => 'image',
                'attr' => [
                    'name' => 'type',
                    'class' => 'form-check-input',
                    'checked' => 'checked',
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
                    'class' => 'file-input image-input',
                ],
            ])
            ->add('secondImageCover', FileType::class, [
                'label' => 'user.fields.secondImageCover',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'file-input image-input',
                ],
            ])
            ->add('thirdImageCover', FileType::class, [
                'label' => 'user.fields.thirdImageCover',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'file-input image-input',
                ],
            ])
            ->add('videoCover', FileType::class, [
                'label' => 'user.fields.videoCover',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'file-input video-input',
                ],
            ])
            ->add('videoYoutubeCover', UrlType::class, [
                'label' => 'user.fields.videoYoutubeCover',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'url youtube',
                ],
                'constraints' => array(
                    new \Symfony\Component\Validator\Constraints\Regex([
                        'pattern' => "#^(http|https)://(www.youtube.com|www.dailymotion.com|vimeo.com)/#",
                        "match" => true,
                        "message" => "L'url doit correspondre à l'url d'une vidéo Youtube, DailyMotion ou Vimeo"
                        ]),
                )
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
