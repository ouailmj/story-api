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
use AppBundle\Exception\FileNotAuthorizedException;
use AppBundle\Form\Type\AvatarType;
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
     */
    public function __invoke(Request $request, MediaManager $mediaManager, UserManager $userManager)
    {
        $user = $this->getUser();
       // $form = $this->factory->create(AvatarType::class, $user);
       // $form->handleRequest($request);
   dump($user);die;
        // This will be handled by API Platform and returns a validation error.
      //  throw new ValidationException($this->validator->validate($form));
    }

}