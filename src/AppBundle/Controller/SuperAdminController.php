<?php

namespace AppBundle\Controller;

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
        return $this->render('@App/superAdmin/layout.html.twig');
    }

    /**
     * Super Admin company list Action
     *
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function companyListAction($page)
    {
        return $this->render('@App/superAdmin/companyList.html.twig', array('page' => $page));
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
