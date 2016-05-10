<?php

namespace AppBundle\Controller;

use AppBundle\Entity\SupplierContact;
use AppBundle\Form\SupplierContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/supplier-contact")
 */

class SupplierContactController extends Controller
{
    /**
     * @Route("/{supplier_id}", requirements={"supplier_id": "\d+"}, name="supplier_contacts_list")
     * @param $supplier_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction($supplier_id)
    {
        $supplier = $this->getDoctrine()->getRepository('AppBundle:Supplier')->find($supplier_id);
        $contacts = $this->getDoctrine()->getRepository('AppBundle:SupplierContact')->findBy(['supplier' => $supplier]);

        return $this->render('AppBundle:SupplierContact:list.html.twig', compact('contacts', 'supplier_id'));
    }

    /**
     * @Route("/create/{supplier_id}", requirements={"supplier_id": "\d+"}, name="supplier_contact_create")
     * @param $supplier_id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction($supplier_id, Request $request)
    {
        $supplier = $this->getDoctrine()->getRepository('AppBundle:Supplier')->find($supplier_id);
        if ($supplier) {
            $contact = new SupplierContact();
            $contact->setSupplier($supplier);
            $form = $this->createForm(SupplierContactType::class, $contact);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($contact);
                $em->flush();
                $this->addFlash('success', 'Contact was added');
                return $this->redirect($this->generateUrl('supplier_update', ['supplier_id' => $supplier_id]));
            }
            return $this->render('AppBundle:SupplierContact:create.html.twig', [
                'form' => $form->createView(),
                'supplier' => $supplier
            ]);
        }

        $this->addFlash('warning', 'Supplier not found');
        return $this->redirect($this->generateUrl('expenses_list'));
    }

    /**
     * @Route("/delete/{id}", requirements={"id": "\d+"}, name="supplier_contact_delete")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction($id)
    {
        return $this->render('AppBundle:SupplierContact:delete.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/update/{id}", requirements={"id": "\d+"}, name="supplier_contact_delete")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAction($id)
    {
        return $this->render('AppBundle:SupplierContact:update.html.twig', array(
            // ...
        ));
    }

}
