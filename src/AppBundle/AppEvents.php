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

namespace AppBundle;

final class AppEvents
{
    /**
     * Event fired when user registered via web.
     *
     * @var string
     */
    const USER_REGISTERED_WEB = 'user.registered_web';

    /**
     * Event fired after a new has been uploaded to an event.
     *
     * @var string
     */
    const EVENT_NEW_MEDIA_UPLOADED = 'appevent.new_media_uploaded';
}
