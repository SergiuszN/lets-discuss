<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Company;
use AppBundle\Entity\User;
use AppBundle\Form\CompanyForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SuperAdminController extends Controller
{
    /**
     * Super Admin index Action
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('@App/superAdmin/index.html.twig');
    }

    /**
     * Super Admin company list Action
     *
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function companyListAction($page)
    {
        $pagination = $this->get('knp_paginator')->paginate(
            $this->getDoctrine()->getRepository(Company::class)->getSuperAdminListQuery(),
            $page,
            $this->getParameter('super_admin_list_per_page')
        );

        return $this->render('@App/superAdmin/companyList.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Super Admin company add Action
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function companyAddAction(Request $request)
    {
        $form = $this->createForm(CompanyForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $data = $form->getData();
            $temporaryPassword = substr(md5(random_bytes(10)), 0, 10);

            $email = $data['email'];
            $name = $data['username'];

            $user = new User();
            $user->addRole('ROLE_COMPANY_ADMIN');
            $user->setPlainPassword($temporaryPassword);
            $user->setEmail($email);
            $user->setUsername($name);
            $user->setEnabled(true);

            $em->persist($user);
            $em->flush();

            $company = new Company();
            $company->setName($data['name']);
            $company->setDescription($data['description']);

            $em->persist($company);
            $em->flush();

            $message = (new \Swift_Message('Registration Email'))
                ->setFrom($this->getParameter('mailer_user'))
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                        '@App/superAdmin/emails/createCompanyAdmin.html.twig',
                        [
                            'name' => $name,
                            'email' => $email,
                            'password' => $temporaryPassword
                        ]
                    ),
                    'text/html'
                );

            $this->get('mailer')->send($message);

            return $this->redirectToRoute('app_super_admin_company_list');
        }

        return $this->render('@App/superAdmin/companyAdd.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Super Admin company edit Action
     *
     * @param int $company
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function companyEditAction($company)
    {
        return $this->render('@App/superAdmin/companyEdit.html.twig', array('company' => $company));
    }

    /**
     * Super Admin company remove Action
     *
     * @param int $company
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function companyRemoveAction($company)
    {
        return $this->render('@App/superAdmin/companyRemove.html.twig', array('company' => $company));
    }
}
