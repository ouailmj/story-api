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

namespace AppBundle\Model;


use Doctrine\ORM\EntityManagerInterface;

class PlanManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * PlanManager constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public function allPlans()
    {
        return $this->entityManager->getRepository('AppBundle:Plan')->findAll();
    }

    /**
     * @param array $criteria
     * @return null|object
     */
    public function findPlanByCriteria(array $criteria)
    {
        return $this->entityManager->getRepository('AppBundle:Plan')->findOneBy($criteria);
    }
}