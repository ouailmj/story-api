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
use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CategoryController
 * @package AppBundle\Controller\Admin
 * @Route("admin/category")
 */
class CategoryController extends BaseController
{

    /**
     *
     * @Route("/", name="category_index")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('AppBundle:Category')->findAll();

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        $forms=array();
        foreach ($categories as $cat)
        {
            $forms[]=[
                'delete'=>$this->createDeleteForm($cat)->createView(),
                'edit'=>$this->createEditForm($cat)->createView(),
            ];
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute("category_index");
        }
        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories,
            'category' => $category,
            'form' => $form->createView(),
            'forms' =>$forms,
            'publicCat' =>$em->getRepository('AppBundle:Category')->getNbCategoryByPrivacy('public')['NB_CATEGORY'],
            'privateCat' =>$em->getRepository('AppBundle:Category')->getNbCategoryByPrivacy('private')['NB_CATEGORY'],
        ]);
    }



    /**
     * Deletes a proposition entity.
     *
     * @Route("/{id}", name="category_delete")
     * @Method("DELETE")
     *
     * @param Request $request
     * @param Category $category
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request,  Category $category)
    {
        $form = $this->createDeleteForm($category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
            $this->addSuccessFlash();
        }

        return $this->redirectToRoute('category_index');
    }

    /**
     * Edit a proposition entity.
     *
     * @Route("/{id}", name="category_edit")
     * @Method("PUT")
     *
     * @param Request $request
     * @param Category $category
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request,  Category $category)
    {
        $form = $this->createEditForm($category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addSuccessFlash();
        }

        return $this->redirectToRoute('category_index');
    }


    /**
     * @param Category $category
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createDeleteForm(Category $category)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('category_delete', ['id' => $category->getId()]))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * @param Category $category
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createEditForm(Category $category)
    {
        return $this->createFormBuilder($category)
            ->setAction($this->generateUrl('category_edit', ['id' => $category->getId()]))
            ->setMethod('PUT')
            ->add('title')
            ->add('description', TextareaType::class)
            ->add('privacy', ChoiceType::class, [
                'label' => 'privacy',
                'attr' => ['class' => 'select-search'],
                'choices' => [
                    'event.fields.private' => 'private',
                    'event.fields.public' => 'public', ],
                'multiple' => false,
                'expanded' => false,
            ])
            ->getForm()
            ;
    }
}