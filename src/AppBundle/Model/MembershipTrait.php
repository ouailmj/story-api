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

namespace AppBundle\Model;

use AppBundle\Entity\MemberShip;

trait MembershipTrait
{
    public function acceptInvitationRequest(int $invitationRequestId): MemberShip
    {
        // TODO: Implement acceptInvitationRequest() method.
    }
}
