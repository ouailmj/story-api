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

namespace AppBundle\Messaging;

class SocketClient
{
    protected $port;

    protected $host;

    protected $dns;


    public function send(string $data)
    {
        // TODO: Implement the socket client.
        $dns = $this->dns();
    }

    private function dns(): string
    {
        return $this->dns = "tcp://" . $this->host . ':' . $this->port;
    }
}
