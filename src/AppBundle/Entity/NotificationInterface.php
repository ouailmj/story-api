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


interface NotificationInterface
{

    public function getObject();
    public function formatMessageToText();
    public function formatMessageToMail();
    public function getSource();
    public function send();
    public function sendBulk();

}