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

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Exception\FileNotAuthorizedException;
use AppBundle\Model\MediaManager;
use AppBundle\Model\UserManager;
use Doctrine\ORM\ORMException;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class ProfileController.
 *
 * @Security("has_role('ROLE_ADMIN')")
 * @Route("/auth/profile/")
 */
class ProfileController extends BaseController
{
    private $eventDispatcher;
    private $userManager;

    public function __construct(EventDispatcherInterface $eventDispatcher, UserManagerInterface $userManager)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->userManager = $userManager;
    }

    /**
     * Show the user.
     *
     * @Route("/")
     */
    public function showAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->redirectToRoute('app_profile_edit');
    }

    /**
     * Edit the user.
     *
     * @Route("edit")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $event = new GetResponseUserEvent($user, $request);
        $this->eventDispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $this->container->get('fos_user.profile.form.factory')->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = new FormEvent($form, $request);
            $this->eventDispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

            $this->userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('app_profile_edit');
                $response = new RedirectResponse($url);
            }

            $this->eventDispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }

        return $this->render('@FOSUser/Profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("delete")
     *
     * @param Request     $request
     * @param UserManager $userManager
     *
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, UserManager $userManager)
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', ['id' => $this->getUser()->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
        $form->handleRequest($request);

        $userManager->deleteUser($this->getUser());
        $this->addSuccessFlash();

        return $this->redirectToRoute('app_default_index');
    }

    /**
     * @Route("avatar")
     *
     * @param Request     $request
     * @param UserManager $userManager
     *
     * @return RedirectResponse|Response
     */
    public function avatarAction(Request $request, UserManager $userManager, MediaManager $mediaManager)
    {
        $user = $this->getUser();
        $form = $this->createForm('AppBundle\Form\Type\AvatarType', $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                /** @var UploadedFile $uploadedImage */
                $uploadedImage = $form->get('avatarIMG')->getData();
                $media = $mediaManager->uploadImage($uploadedImage, $user);
                //$user->setAvatar($media);
                $userManager->updateAvatar($this->getUser(), $media, false, $user->getAvatar());
                $userManager->updateUser($user);
                $this->addSuccessFlash();

                return $this->redirectToRoute('app_profile_edit');
            } catch (FileNotFoundException $exception) {
                $this->get('logger')->addError($exception->getTraceAsString());
                $this->addFlash('error', $this->get('translator')->trans('flash.file_error'));
            } catch (FileNotAuthorizedException $exception) {
                $this->get('logger')->addError($exception->getTraceAsString());
                $this->addFlash('error', $this->get('translator')->trans('flash.file_not_authorized'));
            } catch (ORMException $exception) {
            }
        }

        return $this->render('@FOSUser/Profile/avatar.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
