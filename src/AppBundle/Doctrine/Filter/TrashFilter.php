<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 22/04/2018
 * Time: 16:24
 */

namespace AppBundle\Doctrine\Filter;


use AppBundle\Model\Trashable;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class TrashFilter extends SQLFilter
{

    /**
     * Gets the SQL query part to add to a query.
     *
     * @param ClassMetaData $targetEntity
     * @param string $targetTableAlias
     *
     * @return string The constraint SQL if there is available, empty string otherwise.
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if (!$targetEntity->reflClass->hasProperty('trashedAt')) {
            return "";
        }

        return $targetTableAlias . 'trashedAt IS NOT NULL';
    }
}
