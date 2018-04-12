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

use AppBundle\Entity\User;
use AppBundle\Model\UserManager;
use AppBundle\Utils\RequestUtils;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ChangeTimezoneAction extends BaseAction
{
    /**
     * @var UserManager
     */
    protected $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @param Request $request
     * @param User    $data
     *
     * @throws ORMException
     *
     * @return User
     *
     * @Route(
     *     name="api_change_user_timezone",
     *     path="/users/{id}/change-timezone",
     *     methods={"PUT"},
     *     defaults={
     *         "_api_resource_class"=User::class,
     *         "_api_item_operation_name"="change_user_timezone"
     *     }
     * )
     * @Method("PUT")
     */
    public function __invoke(Request $request, User $data)
    {
        $timezoneId = $this->getTimezoneIdFromRequest($request);
        try {
            $this->userManager->updateTimezoneId($data, $timezoneId);
        } catch (ORMException $exception) {
            throw $exception;
        }

        return $data;
    }

    private function getTimezoneIdFromRequest(Request $request, $key = 'timezoneId')
    {
        $content = RequestUtils::parseRequestContent($request);

        return array_key_exists($key, $content) ? $content[$key] : '';
    }
}
