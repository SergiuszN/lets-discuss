<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Appraise;
use AppBundle\Entity\CompanyWorker;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CompanyAdminController extends Controller
{
    /**
     * Company Admin index Action
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('@App/companyAdmin/index.html.twig');
    }

    /**
     * Company Admin manager list Action
     *
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function managerListAction($page)
    {
        return $this->render('@App/companyAdmin/managerList.html.twig', array('page' => $page));
    }

    /**
     * Company Admin manager add Action
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function managerAddAction()
    {
        return $this->render('@App/companyAdmin/managerAdd.html.twig');
    }

    /**
     * Company Admin manager edit Action
     *
     * TODO PHP Doc oraz rzutowanie na Manager
     * @param $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function managerEditAction($manager)
    {
        return $this->render('@App/companyAdmin/managerEdit.html.twig', array('manager' => $manager));
    }

    /**
     * Company Admin manager remove Action
     *
     * TODO PHP Doc oraz rzutowanie na Manager
     * @param $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function managerRemoveAction($manager)
    {
        return $this->render('@App/companyAdmin/managerRemove.html.twig', array('manager' => $manager));
    }

    /**
     * Company Admin worker list Action
     *
     * TODO PHP Doc oraz rzutowanie na Manager
     * @param $manager
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function workerListAction($manager, $page)
    {
        return $this->render(
            '@App/companyAdmin/workerList.html.twig',
            array(
                'manager' => $manager,
                'page' => $page
            )
        );
    }

    /**
     * Company Admin worker add Action
     *
     * TODO PHP Doc oraz rzutowanie na Manager
     * @param $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function workerAddAction($manager)
    {
        return $this->render('@App/companyAdmin/workerAdd.html.twig', array('manager' => $manager,));
    }

    /**
     * Company Admin worker edit Action
     *
     * TODO PHP Doc oraz rzutowanie na Manager
     * @param $manager
     * @param CompanyWorker $worker
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function workerEditAction($manager, CompanyWorker $worker)
    {
        return $this->render(
            '@App/companyAdmin/workerEdit.html.twig',
            array(
                'manager' => $manager,
                'worker' => $worker
            )
        );
    }

    /**
     * Company Admin worker remove Action
     *
     * @param $manager
     * @param CompanyWorker $worker
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function workerRemoveAction($manager, CompanyWorker $worker)
    {
        return $this->render(
            '@App/companyAdmin/workerRemove.html.twig',
            array(
                'manager' => $manager,
                'worker' => $worker
            )
        );
    }

    /**
     * Company Admin appraise list Action
     *
     * TODO PHP Doc oraz rzutowanie na Manager
     * @param $manager
     * @param CompanyWorker $worker
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function appraiseListAction($manager, CompanyWorker $worker, $page)
    {
        return $this->render(
            '@App/companyAdmin/appraiseList.html.twig',
            array(
                'manager' => $manager,
                'worker' => $worker,
                'page' => $page
            )
        );
    }

    /**
     * Company Admin appraise add Action
     *
     * TODO PHP Doc oraz rzutowanie na Manager
     * @param $manager
     * @param CompanyWorker $worker
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function appraiseAddAction($manager, CompanyWorker $worker)
    {
        return $this->render(
            '@App/companyAdmin/appraiseAdd.html.twig',
            array(
                'manager' => $manager,
                'worker' => $worker
            )
        );
    }

    /**
     * Company Admin appraise edit Action
     *
     * TODO PHP Doc oraz rzutowanie na Manager
     * @param $manager
     * @param CompanyWorker $worker
     * @param Appraise $appraise
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function appraiseEditAction($manager, CompanyWorker $worker, Appraise $appraise)
    {
        return $this->render(
            '@App/companyAdmin/appraiseEdit.html.twig',
            array(
                'manager' => $manager,
                'worker' => $worker,
                'appraise' => $appraise
            )
        );
    }

    /**
     * Company Admin appraise remove Action
     *
     * TODO PHP Doc oraz rzutowanie na Manager
     * @param $manager
     * @param CompanyWorker $worker
     * @param Appraise $appraise
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function appraiseRemoveAction($manager, CompanyWorker $worker, Appraise $appraise)
    {
        return $this->render(
            '@App/companyAdmin/appraiseRemove.html.twig',
            array(
                'manager' => $manager,
                'worker' => $worker,
                'appraise' => $appraise
            )
        );
    }
}
