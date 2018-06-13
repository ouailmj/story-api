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

namespace AppBundle\Action;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use AppBundle\Entity\Event;
use AppBundle\Entity\Image;
use AppBundle\Entity\Video;
use AppBundle\Form\API\EventCoverImageType;
use AppBundle\Model\EventManager;
use AppBundle\Model\MediaManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("has_role('ROLE_USER')")
 */
class EventCoverImageAction extends BaseAction
{
    private $validator;
    private $factory;

    public function __construct( FormFactoryInterface $factory, ValidatorInterface $validator)
    {
        $this->validator = $validator;
        $this->factory = $factory;
    }

    /**
     * @param Event $event
     * @param Request $request
     * @param MediaManager $mediaManager
     * @param EventManager $eventManager
     * @return array|bool
     * @throws \AppBundle\Exception\FileNotAuthorizedException
     */
    public function __invoke(Event $event, Request $request, MediaManager $mediaManager, EventManager $eventManager)
    {
        $form = $this->factory->create(EventCoverImageType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($event->getCreatedBy() !== $this->getUser()) return false;
            $eventManager->clearCover($event);
            $coverType = $form->get('coverType')->getData();

            switch ($coverType)
            {
                case 'video_upload':
                    /** @var UploadedFile $uploadedVideo */
                    $uploadedVideoCover = $form->get('videoCover')->getData();
                    /** @var Video $media */
                    $media = $mediaManager->uploadVideo($uploadedVideoCover, $this->getUser());
                    $event->setVideoGallery($media);
                    break;
                case 'video_youtube':
                    $video  = new Video();
                    $video->setDownloadLink($form->get('videoYoutubeCover')->getData());
                    $video->setSrc($form->get('videoYoutubeCover')->getData());
                    $video->setUploadedAt(new \DateTime());
                    $video->setCreatedBy($this->getUser());
                    $event->setVideoGallery($video);
                    $mediaManager->saveMedia($video, false);
                    break;
                case 'image':
                    $gallery = [$form->get('firstImageCover')->getData(), $form->get('secondImageCover')->getData(), $form->get('thirdImageCover')->getData()];
                    foreach ($gallery as $img) {
                        if (null !== $img) {
                            /** @var Image $media */
                            $media = $mediaManager->uploadImage($img, $this->getUser());
                            $event->addImagesGallery($media);
                        }
                    }
                    break;
                default:
                    return false;
            }

            if ('free' === $event->getEventPurchase()->getPlan()->getPlanKey()) {
                $event->setCurrentStep('invite-friends');
            } else {
                $event->setCurrentStep('payment');
            }

            $this->getDoctrine()->getManager()->flush();

            $responseData = [];
            $responseData['message'] = 'Your event has been updated successfully';
            return $responseData;
        }
        // This will be handled by API Platform and returns a validation error.
        throw new ValidationException($this->validator->validate($form));
    }

}