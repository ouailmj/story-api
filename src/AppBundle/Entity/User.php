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

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User.
 *
 * @ORM\Table(name="app_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 * @ApiResource(
 *     itemOperations={
 *     "get",
 *     "put",
 *     "delete",
 *     },
 *     collectionOperations= {
 *     "post",
 *     "api_current_user"={
 *          "route_name"="currentUserAPI",
 *          "method"="GET"
 *      },
 *     "api_sign_up"={"route_name"="signUpAPI"},
 *     }
 *)
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column( type="string", nullable=true)
     */
    protected $firstName;

    /**
     * @var string
     * @ORM\Column( type="string", nullable=true)
     */
    protected $lastName;

    /**
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true)
     */
    protected $facebookId = '';

    protected $facebookAccessToken;

    /**
     * @ORM\Column(name="google_id", type="string", length=255, nullable=true)
     */
    protected $googleId = '';

    protected $googleAccessToken;

    /**
     * @var string
     * @Assert\Length(max = 20)
     * @ORM\Column( type="string", length=20, nullable=true)
     */
    protected $phoneNumber = '';

    /**
     * @var string
     * @ORM\Column( type="string", length=250, nullable=true)
     */
    protected $fullName = '';

    /**
     * @var string
     * @ORM\Column( type="string", length=50, nullable=true)
     */
    protected $timezoneId = 'Europe/Paris';

    /**
     * @var MemberShip [] | ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MemberShip", mappedBy="member")
     */
    protected $eventMemberShips;

    /**
     * @var Event[] | ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Event", mappedBy="createdBy")
     */
    protected $createdEvents;

    /**
     * @var Payment[] | ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Payment", mappedBy="user")
     */
    protected $payments;

    /**
     * @var InvitationRequest [] | ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\InvitationRequest", mappedBy="user")
     */
    protected $invitationRequests;

    /**
     * @var Media [] | ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Media", mappedBy="createdBy")
     */
    protected $medias;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Media", cascade={"persist", "remove"})
     */
    protected $avatar = null;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->createdEvents = new ArrayCollection();
        $this->invitationRequests = new ArrayCollection();
        $this->eventMemberShips = new ArrayCollection();
        $this->payments = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @param mixed $facebookId
     *
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFacebookAccessToken()
    {
        return $this->facebookAccessToken;
    }

    /**
     * @param mixed $facebookAccessToken
     *
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebookAccessToken = $facebookAccessToken;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGoogleId()
    {
        return $this->googleId;
    }

    /**
     * @param mixed $googleId
     *
     * @return User
     */
    public function setGoogleId($googleId)
    {
        $this->googleId = $googleId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGoogleAccessToken()
    {
        return $this->googleAccessToken;
    }

    /**
     * @param mixed $googleAccessToken
     *
     * @return User
     */
    public function setGoogleAccessToken($googleAccessToken)
    {
        $this->googleAccessToken = $googleAccessToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber(string $phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     */
    public function setFullName(string $fullName)
    {
        $this->fullName = $fullName;
    }

    /**
     * @return string
     */
    public function getTimezoneId()
    {
        return $this->timezoneId;
    }

    /**
     * @param string $timezoneId
     */
    public function setTimezoneId(string $timezoneId)
    {
        $this->timezoneId = $timezoneId;
    }

    /**
     * Add CreatedEvents.
     *
     * @param \AppBundle\Entity\Event $event
     *
     * @return User
     */
    public function addCreatedEvents(Event $event)
    {
        $this->createdEvents[] = $event;

        return $this;
    }

    /**
     * Remove CreatedEvents.
     *
     * @param \AppBundle\Entity\Event $event
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeCreatedEvents(Event $event)
    {
        return $this->createdEvents->removeElement($event);
    }

    /**
     * Get createdEvents.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCreatedEvents()
    {
        return $this->createdEvents;
    }

    /**
     * Add eventMemberShips.
     *
     * @param MemberShip $memberShip
     *
     * @return $this
     */
    public function addEventMemberShips(MemberShip $memberShip)
    {
        $this->eventMemberShips[] = $memberShip;

        return $this;
    }

    /**
     * Remove eventMemberShips.
     *
     * @param \AppBundle\Entity\MemberShip $memberShip
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeEventMemberShips(MemberShip $memberShip)
    {
        return $this->eventMemberShips->removeElement($memberShip);
    }

    /**
     * Get eventMemberShips.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEventMemberShips()
    {
        return $this->eventMemberShips;
    }

    /**
     * Add invitationRequests.
     *
     * @param InvitationRequest $invitationRequest
     *
     * @return $this
     */
    public function addInvitationRequests(InvitationRequest $invitationRequest)
    {
        $this->invitationRequests[] = $invitationRequest;

        return $this;
    }

    /**
     * Remove invitationRequests.
     *
     * @param \AppBundle\Entity\InvitationRequest $invitationRequest
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeInvitationRequests(InvitationRequest $invitationRequest)
    {
        return $this->invitationRequests->removeElement($invitationRequest);
    }

    /**
     * Get invitationRequests.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvitationRequests()
    {
        return $this->invitationRequests;
    }

    /**
     * Add medias.
     *
     * @param Media $media
     *
     * @return $this
     */
    public function addMedias(Media $media)
    {
        $this->medias[] = $media;

        return $this;
    }

    /**
     * Remove invitationRequests.
     *
     * @param \AppBundle\Entity\Media $media
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeMedias(Media $media)
    {
        return $this->medias->removeElement($media);
    }

    /**
     * Get invitationRequests.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * Get the user's timezone instance.
     *
     * @return \DateTimeZone
     */
    public function getTimeZoneInstance()
    {
        try {
            $tz = new \DateTimeZone($this->getTimezoneId());
        } catch (\Exception $exception) {
            return new \DateTimeZone(date_default_timezone_get());
        }

        return $tz;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * Get enabled.
     *
     * @return bool
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Add eventMemberShip.
     *
     * @param \AppBundle\Entity\MemberShip $eventMemberShip
     *
     * @return User
     */
    public function addEventMemberShip(\AppBundle\Entity\MemberShip $eventMemberShip)
    {
        $this->eventMemberShips[] = $eventMemberShip;

        return $this;
    }

    /**
     * Remove eventMemberShip.
     *
     * @param \AppBundle\Entity\MemberShip $eventMemberShip
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeEventMemberShip(\AppBundle\Entity\MemberShip $eventMemberShip)
    {
        return $this->eventMemberShips->removeElement($eventMemberShip);
    }

    /**
     * Add createdEvent.
     *
     * @param \AppBundle\Entity\Event $createdEvent
     *
     * @return User
     */
    public function addCreatedEvent(\AppBundle\Entity\Event $createdEvent)
    {
        $this->createdEvents[] = $createdEvent;

        return $this;
    }

    /**
     * Remove createdEvent.
     *
     * @param \AppBundle\Entity\Event $createdEvent
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeCreatedEvent(\AppBundle\Entity\Event $createdEvent)
    {
        return $this->createdEvents->removeElement($createdEvent);
    }

    /**
     * Add payment.
     *
     * @param \AppBundle\Entity\Payment $payment
     *
     * @return User
     */
    public function addPayment(\AppBundle\Entity\Payment $payment)
    {
        $this->payments[] = $payment;

        return $this;
    }

    /**
     * Remove payment.
     *
     * @param \AppBundle\Entity\Payment $payment
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removePayment(\AppBundle\Entity\Payment $payment)
    {
        return $this->payments->removeElement($payment);
    }

    /**
     * Get payments.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * Add invitationRequest.
     *
     * @param \AppBundle\Entity\InvitationRequest $invitationRequest
     *
     * @return User
     */
    public function addInvitationRequest(\AppBundle\Entity\InvitationRequest $invitationRequest)
    {
        $this->invitationRequests[] = $invitationRequest;

        return $this;
    }

    /**
     * Remove invitationRequest.
     *
     * @param \AppBundle\Entity\InvitationRequest $invitationRequest
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeInvitationRequest(\AppBundle\Entity\InvitationRequest $invitationRequest)
    {
        return $this->invitationRequests->removeElement($invitationRequest);
    }

    /**
     * Add media.
     *
     * @param \AppBundle\Entity\Media $media
     *
     * @return User
     */
    public function addMedia(\AppBundle\Entity\Media $media)
    {
        $this->medias[] = $media;

        return $this;
    }

    /**
     * Remove media.
     *
     * @param \AppBundle\Entity\Media $media
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeMedia(\AppBundle\Entity\Media $media)
    {
        return $this->medias->removeElement($media);
    }
}
