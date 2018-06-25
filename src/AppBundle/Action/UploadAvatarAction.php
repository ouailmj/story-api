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
use AppBundle\Entity\User;
use AppBundle\Exception\FileNotAuthorizedException;
use AppBundle\Form\API\AvatarAPIType;
use AppBundle\Model\MediaManager;
use AppBundle\Model\UserManager;
use Doctrine\ORM\ORMException;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
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
class UploadAvatarAction extends BaseAction
{

    private $validator;
    private $factory;

    public function __construct( FormFactoryInterface $factory, ValidatorInterface $validator)
    {
        $this->validator = $validator;
        $this->factory = $factory;
    }


    /**
     * @param Request $request
     * @param MediaManager $mediaManager
     * @param UserManager $userManager
     * @return array
     * @throws FileNotAuthorizedException
     * @throws ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function __invoke(Request $request, MediaManager $mediaManager, UserManager $userManager)
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $form = $this->factory->create(AvatarAPIType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $uploadedImage */
            $uploadedImage = $form->get('avatar')->getData();
            $media = $mediaManager->uploadAvatar($uploadedImage, $user,false, $user->getAvatar());
            $userManager->updateAvatar($this->getUser(), $media, false);
            $userManager->updateUser($user);

            $responseData = [];
            $responseData['message'] = 'Your event has been updated successfully';
            $responseData['url'] = $media->getDownloadLink();
            $responseData['status'] = true;
            return $responseData;


        }
        // This will be handled by API Platform and returns a validation error.
        throw new ValidationException($this->validator->validate($form));
    }

}