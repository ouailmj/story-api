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
use AppBundle\Entity\User;
use AppBundle\Exception\FileNotAuthorizedException;
use AppBundle\Form\API\UploadMediaInEventType;
use AppBundle\Model\MediaManager;
use AppBundle\Model\UserManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("has_role('ROLE_USER')")
 */
class UploadMediaInEventAction extends BaseAction
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
     * @param UserManager $userManager
     * @return array|bool
     * @throws FileNotAuthorizedException
     */
    public function __invoke(Event $event, Request $request, MediaManager $mediaManager, UserManager $userManager)
    {
        $form = $this->factory->create(UploadMediaInEventType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var User $user
             */
            $user = $this->getUser();
            $isEventJoined = false;
            $isEventCreated = false;
            foreach ($event->getEventMemberShips() as $memberShip ){
                $memberShip->setMember() === $user;
                $isEventJoined = true;
            }
            if($event->getCreatedBy() === $user) $isEventCreated = true;
            if(!$isEventJoined && !$isEventCreated ) return false;

            /** @var UploadedFile $uploadedImage */
            $uploadedImage = $form->get('imageUpload')->getData();
            $media = $mediaManager->uploadImage($uploadedImage, $user,true);
            $event->getUploadedMedias()->add($media);

            $this->getDoctrine()->getManager()->flush();

            $responseData = [];
            $responseData['message'] = 'Your image has been uploaded successfully';
            $responseData['status'] = true;
            return $responseData;


        }
        // This will be handled by API Platform and returns a validation error.
        throw new ValidationException($this->validator->validate($form));
    }

}