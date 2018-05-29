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


class CategoryRepository extends BaseRepository
{
    public function getNbCategoryByPrivacy($privacy)
    {
        $qb = $this->createQueryBuilder('cat');

        $res =
            $qb
                ->distinct()
                ->select('COUNT(cat) as NB_CATEGORY')
                ->Where('cat.privacy = :privacy')
                ->setParameter('privacy', $privacy)

                ->getQuery()
                ->getResult()[0]
        ;

        return $res;
    }

}