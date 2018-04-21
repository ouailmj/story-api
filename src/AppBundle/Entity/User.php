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
 *     "api_sign_up"={"route_name"="signUpAPI"},
 *     },
 *     collectionOperations= {
 *     "api_current_user"={
 *          "route_name"="currentUserAPI",
 *          "method"="GET"
 *      },
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
     * @var InvitationRequest [] | ArrayCollection
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\InvitationRequest")
     * @ORM\JoinTable(name="users_invitation_request",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="invitation_request_id", referencedColumnName="id", unique=true)}
     *      )
     */
    protected $invitationRequests;

    /**
     * @var Media [] | ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Media", mappedBy="createdBy")
     */
    protected $medias;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->createdEvents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->invitationRequests = new \Doctrine\Common\Collections\ArrayCollection();
        $this->eventMemberShips = new \Doctrine\Common\Collections\ArrayCollection();
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
    public function getFirstName(): string
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
    public function getLastName(): string
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
}
