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

namespace AppBundle\Repository;

/**
 * PaymentRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PaymentRepository extends \Doctrine\ORM\EntityRepository
{
    public function getSUMPayment($idEventPurchase)
    {
        $qb = $this->createQueryBuilder('p');

        return
            $qb
                ->select('SUM(p.totalAmount) as somme , ep.id ')
                ->innerJoin('p.eventPurchase', 'ep')
                ->where(
                    '
                        p.eventPurchase = ep.id
                        AND
                        ep.id=:idEP
                    '
                )

                ->setParameter('idEP', $idEventPurchase)
                ->groupBy('ep.id')
                ->orderBy('ep.id', 'DESC')
                ->getQuery()
                ->getResult()
            ;
    }

    public function getAllSUMPayment()
    {
        $qb = $this->createQueryBuilder('p');

        return
            $qb
                ->select('SUM(p.totalAmount) as somme ')
                ->getQuery()
                ->getResult()
            ;
    }
}
