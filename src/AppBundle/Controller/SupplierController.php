<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Supplier;
use AppBundle\Form\SupplierType;

/**
 * @Route("/supplier")
 */

class SupplierController extends Controller
{
    /**
     * @Route("/", name="suppliers_list")
     */
    public function listAction()
    {
        $suppliers = $this->getDoctrine()->getRepository('AppBundle:Supplier')->findAll();
        return $this->render('AppBundle:Supplier:list.html.twig', compact('suppliers'));
    }

    /**
     * @Route("/create", name="supplier_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $account = new Supplier();
        $form = $this->createForm(SupplierType::class, $account);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($account);
            $em->flush();

            return $this->redirect($this->generateUrl('suppliers_list'));
        }
        return $this->render('AppBundle:Account:create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/update", name="supplier_update")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request)
    {
        $id = $request->query->getInt('id');
        $account = $this->getDoctrine()->getRepository('AppBundle:Supplier')->find($id);
        if (!$account) {
            $this->addFlash('warning', 'Supplier was not found');
            return $this->redirectToRoute('suppliers_list', array(), 301);
        }
        $form = $this->createForm(SupplierType::class, $account);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'Supplier was updated');
            return $this->redirectToRoute('suppliers_list', array(), 301);
        }
        return $this->render('AppBundle:Supplier:create.html.twig', ['form' => $form->createView(), 'submit_label' => 'Update']);
    }

    /**
     * @Route("/delete", name="supplier_delete")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request)
    {
        $id = $request->query->getInt('id');
        $supplier = $this->getDoctrine()->getRepository('AppBundle:Supplier')->find($id);
        if (!$supplier) {
            $this->addFlash('warning', 'Supplier was not found');
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->remove($supplier);
            $em->flush();
            $this->addFlash('success', 'Supplier was deleted');
        }
        return $this->redirectToRoute('suppliers_list', array(), 301);
    }

}
