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
use AppBundle\Form\Type\ProfileType;
use AppBundle\Model\MediaManager;
use AppBundle\Model\UserManager;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends BaseController
{
    /**
     * @Route("profile", name="client_profile_edit")
     *
     * @param Request $request
     * @param MediaManager $mediaManager
     * @param UserManager $userManager
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \AppBundle\Exception\FileNotAuthorizedException
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

        $form= $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $user = $this->getUser();
                if($form->get('avatarIMG') !== null) {
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
        ]);
    }

    /**
     * @Route("change_password", name="client_profile_password")
     */
    public function passwordAction()
    {

        return $this->render('client/profile/change_password.html.twig', [

        ]);
    }

}