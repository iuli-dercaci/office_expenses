<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="expenses_list")
     */
    public function listAction()
    {
        $expenses = $this->getDoctrine()->getRepository('AppBundle:Expense')->findAll();
        return $this->render('AppBundle:Expense:list.html.twig', compact('expenses'));
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

}
