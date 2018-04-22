<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 22/04/2018
 * Time: 21:56
 */

namespace AppBundle\Model;


interface Trashable
{
    public function getTrashedAt();
}