<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Appraise;
use AppBundle\Entity\Company;
use AppBundle\Entity\CompanyWorker;
use AppBundle\Entity\User;
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
        $company = $this->getCompany();

        return $this->render('@App/companyAdmin/index.html.twig', [
            'company' => $company
        ]);
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
     * @param User $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function managerEditAction(User $manager)
    {
        return $this->render('@App/companyAdmin/managerEdit.html.twig');
    }

    /**
     * Company Admin manager remove Action
     *
     * @param User $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function managerRemoveAction(User $manager)
    {
        return $this->render('@App/companyAdmin/managerRemove.html.twig');
    }

    /**
     * Company Admin worker list Action
     *
     * @param User $manager
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function workerListAction(User $manager, $page)
    {
        return $this->render('@App/companyAdmin/workerList.html.twig', array('page' => $page));
    }

    /**
     * Company Admin worker add Action
     *
     * @param User $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function workerAddAction(User $manager)
    {
        return $this->render('@App/companyAdmin/workerAdd.html.twig');
    }

    /**
     * Company Admin worker edit Action
     *
     * @param User $manager
     * @param CompanyWorker $worker
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function workerEditAction(User $manager, CompanyWorker $worker)
    {
        return $this->render('@App/companyAdmin/workerEdit.html.twig');
    }

    /**
     * Company Admin worker remove Action
     *
     * @param User $manager
     * @param CompanyWorker $worker
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function workerRemoveAction(User $manager, CompanyWorker $worker)
    {
        return $this->render( '@App/companyAdmin/workerRemove.html.twig');
    }

    /**
     * Company Admin appraise list Action
     *
     * @param User $manager
     * @param CompanyWorker $worker
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function appraiseListAction(User $manager, CompanyWorker $worker, $page)
    {
        return $this->render( '@App/companyAdmin/appraiseList.html.twig', array('page' => $page));
    }

    /**
     * Company Admin appraise add Action
     *
     * @param User $manager
     * @param CompanyWorker $worker
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function appraiseAddAction(User $manager, CompanyWorker $worker)
    {
        return $this->render('@App/companyAdmin/appraiseAdd.html.twig');
    }

    /**
     * Company Admin appraise edit Action
     *
     * @param User $manager
     * @param CompanyWorker $worker
     * @param Appraise $appraise
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function appraiseEditAction(User $manager, CompanyWorker $worker, Appraise $appraise)
    {
        return $this->render('@App/companyAdmin/appraiseEdit.html.twig');
    }

    /**
     * Company Admin appraise remove Action
     *
     * @param User $manager
     * @param CompanyWorker $worker
     * @param Appraise $appraise
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function appraiseRemoveAction(User $manager, CompanyWorker $worker, Appraise $appraise)
    {
        return $this->render('@App/companyAdmin/appraiseRemove.html.twig');
    }

    /**
     * Function return company object for current user
     *
     * @param int $mockUp
     * @return Company
     */
    private function getCompany($mockUp = 5)
    {
        //TODO: change this mock up to $this->getUser()->getCompany()
        return $this
            ->getDoctrine()
            ->getRepository(Company::class)
            ->find($mockUp);
    }
}
