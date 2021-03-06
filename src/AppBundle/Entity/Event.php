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
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use AppBundle\Action\EventCoverImageAction;
use AppBundle\Action\UploadMediaInEventAction;
use AppBundle\Action\EventCoverOneImageAction;

/**
 * Event.
 *
 *
 * @ORM\Table(name="app_event")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ApiResource(
 *     itemOperations={
 *     "get",
 *     "put",
 *     "delete",
 *     },
 *     collectionOperations= {
 *     "post",
 *     "api_new_event"={
 *          "route_name"="newEventAPI",
 *          "method"="GET"
 *      },
 *     "api_incomplete_event"={
 *          "route_name"="incompleteEventAPI",
 *          "method"="GET"
 *      },
 *     "api_is_total_payed"={
 *          "route_name"="isTotalPayedAPI",
 *          "method"="GET"
 *      },
 *     "api_event_cover" = {
 *         "method"="POST",
 *         "path"="/event/event-cover/{id}",
 *         "controller"=EventCoverImageAction::class,
 *         "defaults"={"_api_receive"=false},
 *     },
 *     "api_upload_img_cover" = {
 *         "method"="POST",
 *         "path"="/event/upload-image/{id}/{step}",
 *         "controller"=EventCoverOneImageAction::class,
 *         "defaults"={"_api_receive"=false},
 *     },
 *     "api_upload_media" = {
 *         "method"="POST",
 *         "path"="/event/upload-media/{id}",
 *         "controller"=UploadMediaInEventAction::class,
 *         "defaults"={"_api_receive"=false},
 *     },
 *     "api_event_joined" = {
 *         "method"="GET",
 *          "route_name"="eventJoinedAPI",
 *     },
 *     "api_show_event" = {
 *         "method"="GET",
 *          "route_name"="showEventAPI",
 *     },
 *     }
 * )
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
    private $title = 'Untitled';

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetimetz")
     */
    private $startsAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $startedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetimetz")
     */
    private $endsAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $closedAt;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $description = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $privacy = 'private';

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetimetz")
     */
    private $expiresAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $enabledAt;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $place = '';

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $currentStep = 'choose-plan';

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="createdEvents")
     * @ORM\JoinColumn(name="created_by_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $createdBy;

    /**
     * @var Challenge [] | ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Challenge", mappedBy="event" ,cascade={"persist", "remove"})
     */
    private $challenges;

    /**
     * @var MemberShip [] | ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MemberShip", mappedBy="event",cascade={"persist", "remove"})
     */
    private $eventMemberShips;

    /**
     * @var InvitationRequest[] | ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\InvitationRequest", mappedBy="event", cascade={"all"})
     */
    private $invitationRequests;

    /**
     * @var Image [] | ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Image", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="event_media",
     *      joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="media_id", referencedColumnName="id", unique=true, onDelete="CASCADE")}
     *      )
     */
    private $uploadedMedias;

    /**
     * @var Video
     *
     * @ORM\OneToOne(targetEntity="Video", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="video_gallery_id", referencedColumnName="id")
     */
    private $videoGallery;


    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category"  ).
     * @ORM\ManyToMany(targetEntity="Category")
     * @ORM\JoinTable(name="event_category",
     *      joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id", unique=true)},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     *      )
     */
    private $category ;

    /**
     * @var Image [] | ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Image", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="event_image_gellery",
     *      joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="image_gallery_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $imagesGallery;

    /**
     * @var EventPurchase
     *
     * @ORM\OneToOne(targetEntity="EventPurchase", inversedBy="event", cascade={"persist"})
     */
    private $eventPurchase;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $canceledAt = null;

    /**
     * @var Link
     *
     * @ORM\OneToOne(targetEntity="Link", cascade={"persist", "remove"})
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
     * @return \DateTime
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * @param \DateTime $startedAt
     */
    public function setStartedAt(\DateTime $startedAt)
    {
        $this->startedAt = $startedAt;
    }

    /**
     * @return bool
     */
    public function isStarted()
    {
        if (null === $this->startedAt) {
            return false;
        }
        return true;
    }

    /**
     * @return \DateTime
     */
    public function getClosedAt()
    {
        return $this->closedAt;
    }

    /**
     * @param \DateTime $closedAt
     */
    public function setClosedAt(\DateTime $closedAt)
    {
        $this->closedAt = $closedAt;
    }

    /**
     * @return bool
     */
    public function isClosed()
    {
        if (null === $this->closedAt) {
            return false;
        }
        return true;
    }

    /**
     * @return \DateTime
     */
    public function getEnabledAt()
    {
        return $this->enabledAt;
    }

    /**
     * @param \DateTime $enabledAt
     */
    public function setEnabledAt(\DateTime $enabledAt)
    {
        $this->enabledAt = $enabledAt;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        if (null === $this->enabledAt) {
            return false;
        }
        return false;
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
     * @return string
     */
    public function getCurrentStep(): string
    {
        return $this->currentStep;
    }

    /**
     * @param string $currentStep
     */
    public function setCurrentStep(string $currentStep)
    {
        $this->currentStep = $currentStep;
    }

    /**
     * Set createdBy.
     *
     * @param User $createdBy
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
     * @return User
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
     * @return \Doctrine\Common\Collections\ArrayCollection | MemberShip[]
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
     * Add uploadedMedias.
     *
     * @param Image $media
     *
     * @return $this
     */
    public function addUploadedMedias(Image $media)
    {
        $this->uploadedMedias[] = $media;

        return $this;
    }

    /**
     * Remove uploadedMedias.
     *
     * @param \AppBundle\Entity\Image $media
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeUploadedMedias(Image $media)
    {
        return $this->uploadedMedias->removeElement($media);
    }

    /**
     * Get uploadedMedias.
     *
     * @return Image[]|ArrayCollection
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
        $this->uploadedMedias = $medias;

        return $this;
    }

    /**
     * Set videoGallery.
     *
     * @param Video $videoGallery
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
     * @return Image[] | \Doctrine\Common\Collections\ArrayCollection
     */
    public function getImagesGallery()
    {
        return $this->imagesGallery;
    }

    /**
     * Add
     *
     * @param Image $image
     * @param null $key
     *
     * @return $this
     */
    public function addImagesGallery(Image $image, $key=null)
    {
        if($key != null){
            $this->imagesGallery[$key] = $image;
        }else{
            $this->imagesGallery[] = $image;
        }

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
     * @return EventPurchase
     */
    public function getEventPurchase()
    {
        return $this->eventPurchase;
    }

    /**
     * Set eventPurchase.
     *
     * @param EventPurchase $eventPurchase
     *
     * @return Event
     */
    public function setEventPurchase(EventPurchase $eventPurchase)
    {
        $this->eventPurchase = $eventPurchase;

        return $this;
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
     * @return bool
     */
    public function isCanceled()
    {
        if (null === $this->canceledAt) {
            return false;
        }
        return true;
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

    public function __toString()
    {
        return $this->getTitle().'';
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->challenges = new \Doctrine\Common\Collections\ArrayCollection();
        $this->eventMemberShips = new \Doctrine\Common\Collections\ArrayCollection();
        $this->invitationRequests = new \Doctrine\Common\Collections\ArrayCollection();
        $this->uploadedMedias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->imagesGallery = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add challenge.
     *
     * @param \AppBundle\Entity\Challenge $challenge
     *
     * @return Event
     */
    public function addChallenge(\AppBundle\Entity\Challenge $challenge)
    {
        $this->challenges[] = $challenge;

        return $this;
    }

    /**
     * Remove challenge.
     *
     * @param \AppBundle\Entity\Challenge $challenge
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeChallenge(\AppBundle\Entity\Challenge $challenge)
    {
        return $this->challenges->removeElement($challenge);
    }

    /**
     * Add eventMemberShip.
     *
     * @param \AppBundle\Entity\MemberShip $eventMemberShip
     *
     * @return Event
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
     * Add invitationRequest.
     *
     * @param \AppBundle\Entity\InvitationRequest $invitationRequest
     *
     * @return Event
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
     * Add uploadedMedia.
     *
     * @param \AppBundle\Entity\Media $uploadedMedia
     *
     * @return Event
     */
    public function addUploadedMedia(\AppBundle\Entity\Media $uploadedMedia)
    {
        $this->uploadedMedias[] = $uploadedMedia;

        return $this;
    }

    /**
     * Remove uploadedMedia.
     *
     * @param \AppBundle\Entity\Media $uploadedMedia
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeUploadedMedia(\AppBundle\Entity\Media $uploadedMedia)
    {
        return $this->uploadedMedias->removeElement($uploadedMedia);
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
    }


    /**
     * @return bool
     */
    public function isTotalPayed()
    {
        $sum = 0;
        foreach ($this->eventPurchase->getPayments() as $payment)
        {
            $sum += $payment->getTotalAmount();
        }
        if($sum >= $this->eventPurchase->getPlan()->getPrice()) return true;
        return false;
    }

    public function isMemberShips($user){
        $listMemberShips = $this->getEventMemberShips()->toArray();
        foreach ($listMemberShips as $memberships){
            if($user === $memberships->getMember()) return true;
        }
        return false;
    }

    public  function  isCreator($user){
        return $this->createdBy === $user;
    }

    public function isCreatorOrMemberShips($user){
        return $this->isCreator($user) || $this->isMemberShips($user);
    }



}
