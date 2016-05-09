<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Expense;
use AppBundle\Form\ExpenseType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ExpenseController extends Controller
{
    /**
     * @Route("/", name="expenses_list")
     */
    public function listAction()
    {
        return $this->render('AppBundle:Expense:list.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/create", name="expense_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $expense = new Expense();
        $form = $this->createForm(ExpenseType::class, $expense);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($expense);
            $em->flush();

            return $this->redirect($this->generateUrl('expenses_list'));
        }
        return $this->render('AppBundle:Expense:create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/update", name="expense_update")
     */
    public function updateAction()
    {
        return $this->render('AppBundle:Expense:update.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/delete", name="expense_delete")
     */
    public function deleteAction()
    {
        return $this->render('AppBundle:Expense:delete.html.twig', array(
            // ...
        ));
    }

}
