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

class GalleryPublisher
{
    /** @var SocketClient */
    private $socketClient;

    /**
     * GalleryPublisher constructor.
     *
     * @param SocketClient $socketClient
     */
    public function __construct(SocketClient $socketClient)
    {
        $this->socketClient = $socketClient;
    }

    /**
     * @param $event
     * @param $media
     *
     * @throws \Exception
     */
    public function publishMedia($event, $media)
    {
        try {
            $data = $this->prepareData($event, $media);
            $this->socketClient->send($data);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Publishes without throwing an exception.
     *
     * @param $event
     * @param $media
     *
     * @return bool
     */
    public function publishMediaQuietly($event, $media)
    {
        try {
            $this->publishMedia($event, $media);
        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }

    private function prepareData($event, $media)
    {
        // TODO: implement this.

        return json_encode([]);
    }
}
