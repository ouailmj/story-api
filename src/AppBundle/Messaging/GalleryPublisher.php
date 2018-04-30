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

use Symfony\Component\Serializer\Serializer;

class GalleryPublisher
{
    /** @var Client */
    private $client;
    /** @var Serializer */
    private $serializer;

    /**
     * GalleryPublisher constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client,Serializer $serializer)
    {
        $this->client = $client;
        $this->serializer=$serializer;

    }

    /**
     * @param $event
     * @param $media
     *
     * @throws \Exception
     */
    public function publishMedia($event, $media)
    {
        $data = $this->prepareData($event, $media);
        $this->client->push($data);
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
        $arr = array(
          'event'=>$this->serializer->serialize($event,'json'),
            'media'=>$this->serializer->serialize($media,'json')
        );
        return $this->serializer->serialize($arr,'json');
    }
}
