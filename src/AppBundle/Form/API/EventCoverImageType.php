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

namespace AppBundle\Form\API;


use AppBundle\Entity\Event;
use AppBundle\Entity\Media;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventCoverImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('firstImageCover', FileType::class, [
                'required' => false,
                'mapped' => false
            ])
            ->add('secondImageCover', FileType::class, [
                'required' => false,
                'mapped' => false
            ])
            ->add('thirdImageCover', FileType::class, [
                'required' => false,
                'mapped' => false
            ])

            ->add('videoCover', FileType::class, [
                'required' => false,
                'mapped' => false,
            ])
            ->add('videoYoutubeCover', UrlType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => array(
                    new \Symfony\Component\Validator\Constraints\Regex([
                        'pattern' => "#^(http|https)://(www.youtube.com)/#",
                        "match" => true,
                        "message" => "L'url doit correspondre à l'url d'une vidéo Youtube"
                    ]),
                )
            ])
            ->add('coverType', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Video' => 'video_upload',
                    'VideoYoutube' => 'video_youtube',
                    'Image' => 'image',
                ],
                'multiple' => false,
                'expanded' => true,
                'required' => true,
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

}