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

namespace AppBundle\Controller\Admin;


use AppBundle\Controller\BaseController;
use AppBundle\Entity\PropositionChallenge;
use AppBundle\Form\PropositionChallengeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PropositionChallengeController
 * @package AppBundle\Controller\Admin
 * @Route("admin/proposition-challenge")
 */
class PropositionChallengeController extends BaseController
{
    /**
     *
     * @Route("/", name="proposition_challenge_index")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $propositions = $em->getRepository('AppBundle:PropositionChallenge')->findAll();

        $propositionChallenge = new PropositionChallenge($this->getUser());
        $form = $this->createForm(PropositionChallengeType::class, $propositionChallenge);
        $form->handleRequest($request);

        $forms=array();
        foreach ($propositions as $proposition)
        {
            $forms[]=[
                'delete'=>$this->createDeleteForm($proposition)->createView(),
                'edit'=>$this->createEditForm($proposition)->createView(),
                ];
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($propositionChallenge);
            $em->flush();
            $this->addSuccessFlash();
            return  $this->redirectToRoute('proposition_challenge_index');
        }
        return $this->render('admin/proposition-challenge/index.html.twig', [
            'propositions' => $propositions,
            'proposition' => $propositionChallenge,
            'form' => $form->createView(),
            'forms' =>$forms,
        ]);
    }


    /**
     * Deletes a proposition entity.
     *
     * @Route("/{id}", name="proposition_challenge_delete")
     * @Method("DELETE")
     *
     * @param Request $request
     * @param PropositionChallenge $propositionChallenge
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request,  PropositionChallenge $propositionChallenge)
    {
        $form = $this->createDeleteForm($propositionChallenge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($propositionChallenge);
            $em->flush();
            $this->addSuccessFlash();
        }

        return $this->redirectToRoute('proposition_challenge_index');
    }

    /**
     * Edit a proposition entity.
     *
     * @Route("/{id}", name="proposition_challenge_edit")
     * @Method("PUT")
     *
     * @param Request $request
     * @param PropositionChallenge $propositionChallenge
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request,  PropositionChallenge $propositionChallenge)
    {
        $form = $this->createEditForm($propositionChallenge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addSuccessFlash();
        }

        return $this->redirectToRoute('proposition_challenge_index');
    }

    /**
     * @param PropositionChallenge $propositionChallenge
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createDeleteForm(PropositionChallenge $propositionChallenge)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('proposition_challenge_delete', ['id' => $propositionChallenge->getId()]))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * @param PropositionChallenge $propositionChallenge
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createEditForm(PropositionChallenge $propositionChallenge)
    {
        return $this->createFormBuilder($propositionChallenge)
            ->setAction($this->generateUrl('proposition_challenge_edit', ['id' => $propositionChallenge->getId()]))
            ->setMethod('PUT')
            ->add('description', TextareaType::class)
            ->getForm()
            ;
    }
}