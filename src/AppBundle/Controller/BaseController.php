<?php

/*
 * This file is part of the Instan't App project.
 *
 * (c) Instan't App <contact@instant-app.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    public function addSuccessFlash()
    {
        $msg = $this->get('translator')->trans('flash.success');
        $this->addFlash('success', $msg);

    }

    public function addErrorFlash()
    {
        $msg = $this->get('translator')->trans('flash.error');
        $this->addFlash('error', $msg);
    }

    public function addWarningFlash()
    {
        $msg = $this->get('translator')->trans('flash.warning');
        $this->addFlash('warning', $msg);
    }

    protected function getEM($name = 'default')
    {
        return $this->getDoctrine()->getManager($name);
    }
}
