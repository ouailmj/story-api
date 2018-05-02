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

use ApiPlatform\Core\JsonLd\Serializer\ItemNormalizer;
use AppBundle\Entity\Event;
use AppBundle\Entity\Media;
use AppBundle\Entity\User;
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

    private function prepareData(Event $event, Media $media)
    {
        $data = array(
            '_image'    => 'https://d17fnq9dkz9hgj.cloudfront.net/uploads/2012/11/dv413039-feline-intestinal-lymphoma-outlook-632x475.jpg',
            '_eventId'  => $event->getId(),
            '_name'     => "Farah",
//            'media'     => $this->serializer->normalize($media),
           // 'event'     => $this->serializer->normalize($event)
        );
        return json_encode($data);
    }
}
