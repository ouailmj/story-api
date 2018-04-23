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

interface DriverInterface
{
    /**
     * @param string $data
     * @param int    $mode
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function send(string $data, int $mode);

    /**
     * @param string $data
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function push(string $data);

    /**
     * @param string $data
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function pull(string $data);
}
