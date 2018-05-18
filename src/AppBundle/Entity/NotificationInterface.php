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

namespace AppBundle\Entity;


use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Templating\EngineInterface;

interface NotificationInterface
{

    public function getObject();
    public function formatMessageToText();
    public function formatMessageToMail(EngineInterface $templateEngine, UrlGeneratorInterface $router);
    public function getSource();

}