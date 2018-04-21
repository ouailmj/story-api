<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 21/04/2018
 * Time: 09:13
 */

namespace AppBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class AppCommand extends ContainerAwareCommand
{
    public function __construct(string $name = 'app:command')
    {
        parent::__construct($name);
    }
}