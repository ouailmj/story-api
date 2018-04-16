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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class LocaleController extends BaseController
{
    /**
     * @Route("/locale")
     */
    public function indexAction(Request $request)
    {
        $lang = $request->query->get('lang');

        $session = new Session();
        $session->set('_locale', $lang);

        $url = empty($request->headers->get('referer'))
            ? $this->generateUrl('app_default_index')
            : $request->headers->get('referer')
        ;

        return $this->redirect($url);
    }
}