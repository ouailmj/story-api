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

use AppBundle\Messaging\Driver\DriverInterface;

class Client implements MessagingClientInterface
{
    /** @var DriverInterface */
    private $driver;

    /**
     * MessengerClient constructor.
     *
     * @param DriverInterface $driver
     */
    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @param string $message
     * @param array  $options
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function push(string $message, array $options = [])
    {
        $this->driver->push($message);
    }

    /**
     * @param string $message
     * @param array  $options
     *
     * @throws \Exception
     *
     * @return mixed|void
     */
    public function pull(string $message, array $options = [])
    {
        throw new \LogicException('Not yet implemented');
    }

    /**
     * @param string $message
     * @param array  $options
     *
     * @throws \Exception
     *
     * @return mixed|void
     */
    public function send(string $message, array $options = [])
    {
        throw new \LogicException('Not yet implemented');
    }
}
