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


use AppBundle\Form\ChallengeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventChallengeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('challenges', CollectionType::class, array(
                'entry_type'   => ChallengeType::class,
                'label'        => 'challenge',
                'allow_add'    => true,
                'mapped' => false,
                'allow_delete' => true,
                'required'     => false,
                'attr'         => [
                    'class' => 'my-challenge',
                ],
                'entry_options'    => $options
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_hours' => array(),
        ]);
    }
}