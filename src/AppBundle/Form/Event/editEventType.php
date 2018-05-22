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


use AppBundle\Entity\Challenge;
use AppBundle\Form\ChallengeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class editEventType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => false,
            ])
            ->add('place', TextType::class, [
                'label' => false,
            ])
            ->add('startsAt', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => false,
                'attr' => [
                    'id' => 'startAt',
                ],
            ])
            ->add('endsAt', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => false,
                'attr' => [
                    'id' => 'startAt',
                ],
            ])
            ->add('privacy', ChoiceType::class, [
                'label' => false,
                'attr' => ['class' => 'select-search'],
                'choices' => [
                    'event.fields.private' => 'private',
                    'event.fields.public' => 'public', ],
                'multiple' => false,
                'expanded' => false,
                'mapped' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
            ]);
        /**
         * @var Challenge $challenge
         */
             foreach ($options['data_challenges'] as $challenge){
                $builder->add('challenge'.$challenge->getId() , ChallengeType::class, [

                     'data_challenge' =>  $challenge  ,

                    'label' => 'challenge',
                    'mapped' => false,
                    'required' => false,
                ]);
            }

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Event',
            'data_challenges' => array()
        ]);
    }

}