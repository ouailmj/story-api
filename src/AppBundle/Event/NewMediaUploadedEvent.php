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

namespace AppBundle\Event;

use AppBundle\Entity\Event;
use AppBundle\Entity\Media;

class NewMediaUploadedEvent extends AppBaseEvent
{
    /** @var Event */
    private $event;

    /** @var Media */
    private $media;

    /**
     * NewMediaUploadedEvent constructor.
     *
     * @param Event $event
     * @param Media $media
     */
    public function __construct(Event $event, Media $media)
    {
        $this->event = $event;
        $this->media = $media;
    }

    /**
     * @return Event
     */
    public function getEvent(): Event
    {
        return $this->event;
    }

    /**
     * @param Event $event
     *
     * @return NewMediaUploadedEvent
     */
    public function setEvent(Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @return Media
     */
    public function getMedia(): Media
    {
        return $this->media;
    }

    /**
     * @param Media $media
     *
     * @return NewMediaUploadedEvent
     */
    public function setMedia(Media $media): self
    {
        $this->media = $media;

        return $this;
    }
}
