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

namespace AppBundle\EventSubscriber;

use AppBundle\AppEvents;
use AppBundle\Event\NewMediaUploadedEvent;
use AppBundle\Messaging\GalleryPublisher;
use AppBundle\Model\EventManager;
use AppBundle\Model\MediaManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\VarDumper\VarDumper;

class AppEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var EventManager
     */
    private $eventManager;

    /** @var MediaManager */
    private $mediaManager;
    /** @var GalleryPublisher */
    private $gal;

    /**
     * @var GalleryPublisher
     */
    private $galleryPublisher;

    /**
     * AppEventSubscriber constructor.
     *
     * @param EventManager $eventManager
     * @param MediaManager $mediaManager
     * @param GalleryPublisher $galleryPublisher
     */
    public function __construct(EventManager $eventManager, MediaManager $mediaManager,GalleryPublisher $gal)
    {
        $this->eventManager = $eventManager;
        $this->mediaManager = $mediaManager;
        $this->gal=$gal;
    }


    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            AppEvents::EVENT_NEW_MEDIA_UPLOADED => [
                ['notifyWSServerForNewMedia', 15],
            ],
        ];
    }

    /**
     * Notifies the socket server that a new media is available.
     *
     * @param NewMediaUploadedEvent $event
     * @throws \Exception
     */
    public function notifyWSServerForNewMedia(NewMediaUploadedEvent $event)
    {
        $this->gal->publishMedia($event->getEvent(), $event->getMedia());
        // TODO: This is where we should notify the socket server that a new media has been uploaded.
        $this->galleryPublisher->publishMedia($event->getEvent(), $event->getMedia());
    }
}
