<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Account;
use AppBundle\Form\AccountType;

/**
 * @Route("/account")
 */

class AccountController extends Controller
{
    /**
     * @Route("", name="accounts_list")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $accounts = $this->getDoctrine()->getRepository('AppBundle:Account')->findAll();
        return $this->render('AppBundle:Account:list.html.twig', compact('accounts'));
    }

    /**
     * @Route("/create", name="account_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $account = new Account();
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($account);
            $em->flush();

            return $this->redirect($this->generateUrl('accounts_list'));
        }
        return $this->render('AppBundle:Account:create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/update", name="account_update")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request)
    {
        $id = $request->query->getInt('id');
        $account = $this->getDoctrine()->getRepository('AppBundle:Account')->find($id);
        if (!$account) {
            $this->addFlash('warning', 'Account was not found');
            return $this->redirectToRoute('accounts_list', array(), 301);
        }
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'Account was updated');
            return $this->redirectToRoute('accounts_list', array(), 301);
        }
        return $this->render('AppBundle:Account:create.html.twig', ['form' => $form->createView(), 'submit_label' => 'Update']);
    }

    /**
     * @Route("/delete", name="account_delete")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request)
    {
        $id = $request->query->getInt('id');
        $account = $this->getDoctrine()->getRepository('AppBundle:Account')->find($id);
        if (!$account) {
            $this->addFlash('warning', 'Account was not found');
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->remove($account);
            $em->flush();
            $this->addFlash('success', 'Account was deleted');
        }
        return $this->redirectToRoute('accounts_list', array(), 301);
    }

}
