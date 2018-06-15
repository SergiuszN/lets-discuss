<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Appraise;
use AppBundle\Entity\Company;
use AppBundle\Entity\CompanyWorker;
use AppBundle\Entity\User;
use AppBundle\Form\CompanyManagerForm;
use AppBundle\Form\CompanyWorkerForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
        $company = $this->getCompany();

        $pagination = $this->get('knp_paginator')->paginate(
            $this->getDoctrine()->getRepository(User::class)->getCompanyManagersQuery($company),
            $page,
            $this->getParameter('super_admin_list_per_page')
        );

        return $this->render('@App/companyAdmin/managerList.html.twig', [
            'company' => $company,
            'pagination' => $pagination
        ]);
    }

    /**
     * Company Admin manager add Action
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function managerAddAction(Request $request)
    {
        $company = $this->getCompany();

        $form = $this->createForm(CompanyManagerForm::class);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();
            $email = $em->getRepository(User::class)->findBy(array('email'=>$data['email']));

            if(!empty($email)){
                $this->addFlash('danger', 'Email adress already exists. Try another email.');
            }else{
                $temporaryPassword = substr(md5(random_bytes(10)), 0, 10);

                $user = new User();
                $user->addRole('ROLE_COMPANY_MANAGER');
                $user->setPlainPassword($temporaryPassword);
                $user->setEmail($data['email']);
                $user->setUsername($data['username']);
                $user->setEnabled(true);
                $user->setCompany($company);

                $em->persist($user);
                $em->flush();

                $message = (new \Swift_Message('Registration Email'))
                    ->setFrom($this->getParameter('mailer_user'))
                    ->setTo($data['email'])
                    ->setBody(
                        $this->renderView(
                            '@App/companyAdmin/emails/createCompanyManager.html.twig',
                            [
                                'company' => $company,
                                'name' => $data['username'],
                                'password' => $temporaryPassword
                            ]
                        ),
                        'text/html'
                    );

                $this->get('mailer')->send($message);

                return $this->redirectToRoute('app_company_admin_manager_list');
            }

        }

        return $this->render('@App/companyAdmin/managerAdd.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Company Admin manager edit Action
     *
     * @param Request $request
     * @param User $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function managerEditAction(Request $request, User $manager)
    {
        $company = $this->getCompany();

        //TODO: check is user from this company

        $form = $this->createForm(CompanyManagerForm::class, $manager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $user = $form->getData();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_company_admin_manager_list');
        }

        return $this->render('@App/companyAdmin/managerEdit.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Company Admin manager remove Action
     *
     * @param User $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function managerRemoveAction(User $manager)
    {
        $company = $this->getCompany();
        //TODO: check is user from this company

        $em = $this->getDoctrine()->getManager();
        $em->remove($manager);
        $em->flush();

        return $this->redirectToRoute('app_company_admin_manager_list');
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
        $company = $this->getCompany();

        $pagination = $this->get('knp_paginator')->paginate(
            $company->getWorkers(),
            $page,
            $this->getParameter('company_admin_list_per_page')
        );

        return $this->render('@App/companyAdmin/workerList.html.twig', [
            'company' => $company,
            'pagination' => $pagination
        ]);
    }

    /**
     * Company Admin worker add Action
     *
     * @param User $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function workerAddAction(Request $request, User $manager)
    {
        $company = $this->getCompany();
        $form = $this->createForm(CompanyWorkerForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();

            $companyWorker = new CompanyWorker();
            $companyWorker->setName($data['name']);
            $companyWorker->setSurname($data['surname']);
            $companyWorker->setCompany($company);

            $em->persist($companyWorker);
            $em->flush();

            return $this->redirectToRoute('app_company_admin_worker_list', ['manager' => $manager->getId()]);
        }

        return $this->render('@App/companyAdmin/workerAdd.html.twig', [
            'company' => $company,
            'form' => $form->createView()
        ]);
    }

    /**
     * Company Admin worker edit Action
     *
     * @param Request       $request
     * @param User          $manager
     * @param CompanyWorker $worker
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function workerEditAction(Request $request, User $manager, CompanyWorker $worker)
    {
        $form = $this->createForm(CompanyWorkerForm::class, $worker);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $editedWorker = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($editedWorker);
            $em->flush();

            return $this->redirectToRoute('app_company_admin_worker_list', ['manager' => $manager->getId()]);
        }

        return $this->render('@App/companyAdmin/workerEdit.html.twig', [
            'form' => $form->createView(),
            'company' => $this->getCompany()
        ]);
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
        $em = $this->getDoctrine()->getManager();
        $em->remove($worker);
        $em->flush();

        return $this->redirectToRoute('app_company_admin_worker_list', ['manager' => $manager->getId()]);
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
     * @return Company
     */
    private function getCompany()
    {
        return $this
            ->getUser()
            ->getCompany();
    }
}
