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
        $this->addFlash('success', 'Votre opération a été exécutée avec succès');
    }

    public function addErrorFlash()
    {
        $this->addFlash('error', 'Votre opération n\' a pas pu être exécutée.');
    }

    public function addWarningFlash()
    {
        $this->addFlash('warning', 'Votre opération n\' a pas pu être exécutée.');
    }

    protected function getEM($name = 'default')
    {
        return $this->getDoctrine()->getManager($name);
    }

}
