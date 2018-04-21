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

use Doctrine\ORM\EntityNotFoundException;

/**
 * BaseRepository.
 *
 * This class serves as base for repository classes.
 */
class BaseRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return object
     */
    public function findOneOrFail($id)
    {
        $object = $this->find($id);

        if (null !== $object) {
            return $object;
        }

        throw new EntityNotFoundException($this->getClassName(), [$id]);
    }
}
