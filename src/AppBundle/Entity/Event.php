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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Event.
 *
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventRepository")
 */
class Event
{
    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $startsAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $endsAt;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $privacy = 'private';

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $expiresAt;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $place;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="createdEvents" )
     *
     */
    private $createdBy;

    /**
     * @var Challenge [] | ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Challenge", mappedBy="event")
     */
    private $challenges;

    /**
     * @var MemberShip [] | ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MemberShip", mappedBy="event")
     */
    private $eventMemberShips;

    /**
     * @var InvitationRequest [] | ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\InvitationRequest")
     * @ORM\JoinTable(name="event_invitation_request",
     *      joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $invitationRequests;

    /**
     * @var Media [] | ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Media")
     * @ORM\JoinTable(name="event_media",
     *      joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="media_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $uploadedMedias;

    /**
     * @var Video
     *
     * @ORM\OneToOne(targetEntity="Video")
     * @ORM\JoinColumn(name="video_gallery_id", referencedColumnName="id")
     */
    private $videoGallery;

    /**
     * @var Image [] | ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Image")
     * @ORM\JoinTable(name="event_image_gellery",
     *      joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="image_gallery_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $imagesGallery;

    /**
     * @var EventPurchase
     *
     * @ORM\OneToOne(targetEntity="EventPurchase")
     *
     */
    private $eventPurchase;

    /**
     * @var \DateTime
     *
     * @ORM\Column( type="datetime")
     */
    private $canceledAt = null;

    /**
     * @var Link
     *
     * @ORM\OneToOne(targetEntity="Link")
     * @ORM\JoinColumn(name="link_id", referencedColumnName="id")
     */
    private $link;

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
     * Set title.
     *
     * @param string $title
     *
     * @return Event
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set startsAt.
     *
     * @param \DateTime $startsAt
     *
     * @return Event
     */
    public function setStartsAt($startsAt)
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    /**
     * Get startsAt.
     *
     * @return \DateTime
     */
    public function getStartsAt()
    {
        return $this->startsAt;
    }

    /**
     * Set endsAt.
     *
     * @param \DateTime $endsAt
     *
     * @return Event
     */
    public function setEndsAt($endsAt)
    {
        $this->endsAt = $endsAt;

        return $this;
    }

    /**
     * Get endsAt.
     *
     * @return \DateTime
     */
    public function getEndsAt()
    {
        return $this->endsAt;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Event
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set privacy.
     *
     * @param string $privacy
     *
     * @return Event
     */
    public function setPrivacy($privacy)
    {
        $this->privacy = $privacy;

        return $this;
    }

    /**
     * Get privacy.
     *
     * @return string
     */
    public function getPrivacy()
    {
        return $this->privacy;
    }

    /**
     * Set expiresAt.
     *
     * @param \DateTime $expiresAt
     *
     * @return Event
     */
    public function setExpiresAt($expiresAt)
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * Get expiresAt.
     *
     * @return \DateTime
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * Set place.
     *
     * @param string $place
     *
     * @return Event
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place.
     *
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set createdBy.
     *
     * @param string $createdBy
     *
     * @return Event
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy.
     *
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Get challenges.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChallenges()
    {
        return $this->challenges;
    }

    /**
     * Add challenges.
     *
     * @param Challenge $challenge
     *
     * @return $this
     */
    public function addChallenges(Challenge $challenge)
    {
        $this->challenges[] = $challenge;

        return $this;
    }

    /**
     * Remove challenges.
     *
     * @param \AppBundle\Entity\Challenge $challenge
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeChallenges(Challenge $challenge)
    {
        return $this->challenges->removeElement($challenge);
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
     * Add eventMemberShips.
     *
     * @param MemberShip $eventMemberShip
     *
     * @return $this
     */
    public function addEventMemberShips(MemberShip $eventMemberShip)
    {
        $this->eventMemberShips[] = $eventMemberShip;

        return $this;
    }

    /**
     * Remove eventMemberShips.
     *
     * @param \AppBundle\Entity\MemberShip $eventMemberShip
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeEventMemberShips(MemberShip $eventMemberShip)
    {
        return $this->eventMemberShips->removeElement($eventMemberShip);
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
     * Add uploadedMedias.
     *
     * @param Media $media
     *
     * @return $this
     */
    public function addUploadedMedias(Media $media)
    {
        $this->uploadedMedias[] = $media;

        return $this;
    }

    /**
     * Remove uploadedMedias.
     *
     * @param \AppBundle\Entity\Media $media
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeUploadedMedias(Media $media)
    {
        return $this->uploadedMedias->removeElement($media);
    }

    /**
     * Get uploadedMedias.
     *
     * @return Media[]|ArrayCollection
     */
    public function getUploadedMedias()
    {
        return $this->uploadedMedias;
    }

    /**
     * Set UploadedMedias.
     *
     * @param array $medias
     *
     * @return Event
     */
    public function setUploadedMedias($medias)
    {
        $this->uploadedMedias =$medias;
        return $this;
    }

    /**
     * Set videoGallery.
     *
     * @param array $videoGallery
     *
     * @return Event
     */
    public function setVideoGallery($videoGallery)
    {
        $this->videoGallery = $videoGallery;

        return $this;
    }

    /**
     * Get videoGallery.
     *
     * @return Video
     */
    public function getVideoGallery()
    {
        return $this->videoGallery;
    }

    /**
     * Set imagesGallery.
     *
     * @param Video $imagesGallery
     *
     * @return Event
     */
    public function setImagesGallery($imagesGallery)
    {
        $this->imagesGallery = $imagesGallery;

        return $this;
    }

    /**
     * Get imagesGallery.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImagesGallery()
    {
        return $this->imagesGallery;
    }

    /**
     * Add imagesGallery.
     *
     * @param Image $image
     *
     * @return $this
     */
    public function addImagesGallery(Image $image)
    {
        $this->imagesGallery[] = $image;

        return $this;
    }

    /**
     * Remove imagesGallery.
     *
     * @param \AppBundle\Entity\Image $image
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeImagesGallery(Image $image)
    {
        return $this->imagesGallery->removeElement($image);
    }

    /**
     * Get eventPurchase.
     *
     * @return string
     */
    public function getEventPurchase()
    {
        return $this->eventPurchase;
    }

    /**
     * Set EventPurchase.
     *
     * @param  EventPurchase $eventPurchase
     *
     * 
     */
    public function setEventPurchase(EventPurchase $eventPurchase)
    {
        $this->eventPurchase = $eventPurchase;
    }

    /**
     * Set canceledAt.
     *
     * @param \DateTime $canceledAt
     *
     * @return Event
     */
    public function setCanceledAt($canceledAt)
    {
        $this->canceledAt = $canceledAt;

        return $this;
    }

    /**
     * Get canceledAt.
     *
     * @return \DateTime
     */
    public function getCanceledAt()
    {
        return $this->canceledAt;
    }

    /**
     * @return Link
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param Link $link
     */
    public function setLink(Link $link)
    {
        $this->link = $link;
    }
}
