<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
