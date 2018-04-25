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

use AppBundle\Entity\Challenge;
use AppBundle\Entity\Event;
use AppBundle\Entity\InvitationRequest;
use AppBundle\Entity\Media;
use AppBundle\Entity\MemberShip;
use AppBundle\Entity\Payment;
use AppBundle\Entity\Plan;
use AppBundle\Entity\User;

interface EventManagerInterface
{
    public function createEvent(Plan $plan, User $createdBy = null): Event;

    public function createEventWithCustomPlan(User $createdBy = null): Event;

    public function switchEventPlan(int $eventId, Plan $newPlan): Event;

    public function deleteEvent(int $eventId, $hard = false);

    public function addMedia(int $eventId, Media $media): Event;

    public function addMedias(int $eventId, array $medias): Event;

    public function addPayment(int $eventId, Payment $payment): Event;

    public function addPayments(int $eventId, array $payments): Event;

    public function addChallenge(int $eventId, Challenge $challenge): Event;

    public function removeChallenge(int $challengeId);

    public function addMembership(int $eventId, MemberShip $memberShip): Event;

    public function removeMembership(int $memberShipId): Event;

    public function addMemberships(int $eventId, array $memberShips): Event;

    public function startEvent(int $eventId): Event;

    public function closeEvent(int $eventId): Event;

    /**
     * Events are enabled only when they are fully payed.
     *
     * @param int $eventId
     *
     * @return Event
     */
    public function enableEvent(int $eventId): Event;

    /**
     * Returns the path of a zip file that contains all media.
     *
     * @param int $eventId
     *
     * @return string
     */
    public function downloadMedias(int $eventId): string;

    /**
     * Add a new invitation request from a new email.
     *
     * @param int    $eventId
     * @param string $email
     * @param bool   $andSend
     *
     * @return InvitationRequest
     */
    public function addEmailInvitationRequest(int $eventId, string $email, $andSend = false): InvitationRequest;

    /**
     * @param int $invitationRequestId
     *
     * @return mixed
     */
    public function cancelInvitationRequest(int $invitationRequestId);

    /**
     * @param int $eventId
     *
     * @return mixed
     */
    public function trashEvent(int $eventId);

    /**
     * @param int $trashedEventId
     *
     * @return mixed
     */
    public function unTrashEvent(int $trashedEventId);
}
