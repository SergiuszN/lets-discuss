<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function companyAddAction()
    {
        return $this->render('@App/superAdmin/companyAdd.html.twig');
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
