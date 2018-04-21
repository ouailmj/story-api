<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 20/04/2018
 * Time: 19:19
 */

namespace AppBundle\Messaging;


class GalleryPublisher
{
    /** @var SocketClient */
    private $socketClient;

    /**
     * GalleryPublisher constructor.
     * @param SocketClient $socketClient
     */
    public function __construct(SocketClient $socketClient)
    {
        $this->socketClient = $socketClient;
    }

    /**
     * @param $event
     * @param $media
     * @throws \Exception
     */
    public function publishMedia($event, $media)
    {
        try{
            $data = $this->prepareData($event, $media);
            $this->socketClient->send($data);
        }catch (\Exception $exception){
            throw $exception;
        }
    }

    /**
     * Publishes without throwing an exception.
     *
     * @param $event
     * @param $media
     * @return bool
     */
    public function publishMediaSafely($event, $media)
    {
        try{
            $this->publishMedia($event, $media);
        }catch (\Exception $exception){
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