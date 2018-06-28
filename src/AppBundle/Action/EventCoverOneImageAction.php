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
use AppBundle\Form\API\UploadImageGalleryType;
use AppBundle\Model\EventManager;
use AppBundle\Model\MediaManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

class EventCoverOneImageAction extends BaseAction
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
        $form = $this->factory->create(UploadImageGalleryType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($event->getCreatedBy() !== $this->getUser()) return false;

            $imageCover = $form->get('imageFile')->getData() ;
            $step = $request->get('step');
            switch ($step){
                case 'firstImageCover':
                    $key = 0;
                    break;
                case 'secondImageCover':
                    $key = 1;
                    break;
                case 'thirdImageCover':
                    $key = 2;
                    break;
                default:
                    $key=0;
            }
            $eventManager->clearCoverByStep($event, $key);
            if ($imageCover != null) {
                /** @var Image $media */
                $media = $mediaManager->uploadImage($imageCover, $this->getUser());
                $event->addImagesGallery($media, $key);
            }

            if ($step === 'thirdImageCover') {
                if ('free' === $event->getEventPurchase()->getPlan()->getPlanKey()) {
                    $event->setCurrentStep('invite-friends');
                } else {
                    $event->setCurrentStep('payment');
                }
            }

            $this->getDoctrine()->getManager()->flush();

            $responseData = [];
            $responseData['message'] = 'Your event has been updated successfully';
            $responseData['status'] = true;
            return $responseData;

        }
        // This will be handled by API Platform and returns a validation error.
        throw new ValidationException($this->validator->validate($form));
    }

}