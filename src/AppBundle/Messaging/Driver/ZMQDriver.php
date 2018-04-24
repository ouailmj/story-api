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

namespace AppBundle\Messaging\Driver;

class ZMQDriver implements DriverInterface
{
    private $port;

    private $host;

    private $schema;

    private $dns;

    /**
     * ZMQDriver constructor.
     *
     * @param $port
     * @param $host
     * @param $schema
     */
    public function __construct($port = 8888, $host = '127.0.0.1', $schema = 'tcp')
    {
        $this->port = $port;
        $this->host = $host;
        $this->schema = $schema;
        $this->dns = $this->schema.'://'.$this->host.':'.$this->port;
    }

    /**
     * @param string $data
     * @param int    $mode
     *
     * @throws \ZMQSocketException
     */
    public function send(string $data, int $mode)
    {
        $socket = $this->socket($mode);

        $socket->send($data);
    }

    /**
     * @param int  $type
     * @param null $persistentId
     *
     * @throws \ZMQSocketException
     *
     * @return \ZMQSocket
     */
    private function socket(int $type, $persistentId = null): \ZMQSocket
    {
        if (!class_exists(\ZMQ::class)) {
            throw new \LogicException('The zmq extension is not installed. You need to install the pecl extension.');
        }
        $context = new \ZMQContext();
        $socket = $context->getSocket($type, $persistentId);
        $socket->connect($this->dns());

        return $socket;
    }

    private function dns(): string
    {
        if (empty($this->dns)) {
            return $this->schema.'://'.$this->host.':'.$this->port;
        }

        return $this->dns;
    }

    /**
     * @param string $data
     *
     * @throws \ZMQSocketException
     */
    public function push(string $data)
    {
        $socket = $this->pushSocket(null);
        $socket->send($data);
    }

    /**
     * @param null $persistentId
     *
     * @throws \ZMQSocketException
     *
     * @return \ZMQSocket
     */
    private function pushSocket($persistentId = null): \ZMQSocket
    {
        return $this->socket(\ZMQ::SOCKET_PUSH, $persistentId);
    }

    /**
     * @param string $data
     */
    public function pull(string $data)
    {
        throw new \LogicException('Not yet implemented !');
    }
}
