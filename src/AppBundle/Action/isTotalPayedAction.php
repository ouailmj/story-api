<?php
/**
 * Created by PhpStorm.
 * User: soufiane
 * Date: 22/06/18
 * Time: 21:23
 */

namespace AppBundle\Action;

use AppBundle\Entity\Event;
use AppBundle\Model\Payment\PaymentManager;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;


class isTotalPayedAction extends BaseAction
{




    /**
     * @Route(
     *     name="isTotalPayedAPI",
     *     path="/is-total-payed/{id}",
     *     defaults={
     *          "_api_resource_class"=Event::class,
     *          "_api_collection_operation_name"="api_is_total_payed"
     *     },
     *
     * )
     * @Method({"GET"})
     * @Security("has_role('ROLE_USER')")
     */
    public function __invoke(Request $request, Event $event, PaymentManager $paymentManager)
    {

        return
        json_encode(['isPayed'=>$paymentManager->isTotalPayed($event)]);
    }
}