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

namespace AppBundle\Controller\Client;


use AppBundle\Controller\BaseController;
use AppBundle\Exception\FileNotAuthorizedException;
use AppBundle\Form\Type\ChangePasswordType;
use AppBundle\Form\Type\ProfileType;
use AppBundle\Model\MediaManager;
use AppBundle\Model\UserManager;
use Doctrine\ORM\ORMException;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProfileController
 * @package AppBundle\Controller\Client
 *
 * @Route("app/")
 */
class ProfileController extends BaseController
{

    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route("profile", name="client_profile_edit")
     *
     * @param Request $request
     * @param MediaManager $mediaManager
     * @param UserManager $userManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, MediaManager $mediaManager, UserManager $userManager)
    {
        $form = $this->createForm(ProfileType::class, $this->getUser() );
        $form   ->add('avatarIMG', FileType::class, [
            'label' => 'user.fields.avatar',
            'required' => false,
            'mapped' => false,
            'attr' => [
                'class' => 'file-input',
                'onChange' => 'readURL(this);',
                ],
            ]);

        $delete_form = $this->deleteClient();

        $delete_form->handleRequest($request);
        $form= $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $user = $this->getUser();
                if($form->get('avatarIMG')->getData() !== null) {
                    /** @var UploadedFile $uploadedImage */
                    $uploadedImage = $form->get('avatarIMG')->getData();
                    $media = $mediaManager->uploadImage($uploadedImage, $user);
                    $user->setAvatar($media);
                }
                $userManager->updateUser($user);
                $this->addSuccessFlash();
                return $this->redirectToRoute('client_profile_edit');
            } catch (FileNotFoundException $exception) {
                $this->get('logger')->addError($exception->getTraceAsString());
                $this->addFlash('error', $this->get('translator')->trans('flash.file_error'));
            } catch (FileNotAuthorizedException $exception) {
                $this->get('logger')->addError($exception->getTraceAsString());
                $this->addFlash('error', $this->get('translator')->trans('flash.file_not_authorized'));
            } catch (ORMException $exception) {
            }
        }
        return $this->render('client/profile/index.html.twig', [
            'form' => $form->createView(),
            'delete_form' => $delete_form->createView(),
        ]);
    }

    /**
     * @Route("change_password", name="client_profile_password")
     */
    public function passwordAction(Request $request, UserManager $userManager)
    {
        $form = $this->createForm(ChangePasswordType::class, $this->getUser());
        $form= $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $event = new FormEvent($form, $request);
            $this->eventDispatcher->dispatch(FOSUserEvents::CHANGE_PASSWORD_SUCCESS, $event);

            $userManager->updateUser($this->getUser());
            $this->addSuccessFlash();

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('client_profile_password');
                $response = new RedirectResponse($url);
            }

            $this->eventDispatcher->dispatch(FOSUserEvents::CHANGE_PASSWORD_COMPLETED, new FilterUserResponseEvent($this->getUser(), $request, $response));

            return $response;
        }

        return $this->render('client/profile/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("delete-account", name="client_profile_delete_account")
     *
     * @param Request $request
     * @param UserManager $userManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, UserManager $userManager)
    {
        $form = $this->deleteClient();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->deleteUser($this->getUser());
            $this->addSuccessFlash();
        }

        return $this->redirectToRoute('client_profile_edit');
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    private function deleteClient()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('client_profile_delete_account'))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

}