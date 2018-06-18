<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Appraise;
use AppBundle\Entity\Company;
use AppBundle\Entity\CompanyWorker;
use AppBundle\Entity\User;
use AppBundle\Form\AppraiseForm;
use AppBundle\Form\CompanyManagerForm;
use AppBundle\Form\CompanyWorkerForm;
use AppBundle\Form\RemoveConfirmationForm;
use AppBundle\Interfaces\AuditInterface;
use AppBundle\Traits\AuditTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CompanyAdminController extends Controller implements AuditInterface
{
    use AuditTrait;

    /**
     * Company Admin index Action
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $company = $this->getCompany();

        $this->saveAudit([]);
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

        $this->saveAudit(['page' => $page]);
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
            $this->saveAudit(['data' => $data]);
            $email = $em->getRepository(User::class)->findBy(array('email'=>$data['email']));

            if (!empty($email)) {
                $this->addFlash('danger', 'Email adress already exists. Try another email.');
                return $this->render('@App/companyAdmin/managerAdd.html.twig', [
                    'company' => $company,
                    'form' => $form->createView(),
                ]);
            }

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

        $this->saveAudit([]);
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

            $this->saveAudit(['user' => $user, 'manager' => $manager]);
            return $this->redirectToRoute('app_company_admin_manager_list');
        }

        $this->saveAudit([]);
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
        if ($this->getCompany()->getId() === $manager->getCompany()->getId()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($manager);
            $em->flush();
        }

        $this->saveAudit(['manager' => $manager]);
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

        $this->saveAudit(['manager' => $manager, 'page' => $page]);
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

            $this->saveAudit(['manager' => $manager, 'data' => $data]);
            return $this->redirectToRoute('app_company_admin_worker_list', ['manager' => $manager->getId()]);
        }

        $this->saveAudit(['manager' => $manager]);
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

            $this->saveAudit(['manager' => $manager, 'worker' => $worker, 'data' => $editedWorker]);
            return $this->redirectToRoute('app_company_admin_worker_list', ['manager' => $manager->getId()]);
        }

        $this->saveAudit(['manager' => $manager, 'worker' => $worker]);
        return $this->render('@App/companyAdmin/workerEdit.html.twig', [
            'form' => $form->createView(),
            'company' => $this->getCompany()
        ]);
    }

    /**
     * Company Admin worker remove Action
     *
     * @param Request       $request
     * @param User          $manager
     * @param CompanyWorker $worker
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function workerRemoveAction(Request $request, User $manager, CompanyWorker $worker)
    {
        $form = $this->createForm(RemoveConfirmationForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /** @var Appraise $appraise */
            foreach ($worker->getAppraisals() as $appraise) {
                $em->remove($appraise);
            }

            $em->remove($worker);
            $em->flush();

            $this->saveAudit(['manager' => $manager, 'worker' => $worker]);
            return $this->redirectToRoute('app_company_admin_worker_list', [
                'manager' => $manager->getId()
            ]);
        }

        return $this->render('@App/companyAdmin/workerRemove.html.twig', [
            'company' => $this->getCompany(),
            'worker' => $worker,
            'form' => $form->createView(),
        ]);
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
        $pagination = $this->get('knp_paginator')->paginate(
            $worker->getAppraisals(),
            $page,
            $this->getParameter('company_admin_list_per_page')
        );

        $this->saveAudit(['manager' => $manager, 'worker' => $worker, 'page' => $page]);
        return $this->render( '@App/companyAdmin/appraiseList.html.twig', [
            'pagination' => $pagination,
            'worker' => $worker,
            'company' => $this->getCompany()
        ]);
    }

    /**
     * Company Admin appraise add Action
     *
     * @param Request       $request
     * @param User          $manager
     * @param CompanyWorker $worker
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function appraiseAddAction(Request $request, User $manager, CompanyWorker $worker)
    {
        $form = $this->createForm(AppraiseForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();

            $appraise = new Appraise();
            $appraise->setRate($data['rate']);
            $appraise->setDescription($data['description']);
            $appraise->setDate(new \DateTime());
            $appraise->setCompanyWorker($worker);

            $em->persist($appraise);
            $em->flush();

            $this->saveAudit(['manager' => $manager, 'worker' => $worker, 'data' => $data]);
            return $this->redirectToRoute('app_company_admin_appraise_list', [
                'manager' => $manager->getId(),
                'worker' => $worker->getId(),
            ]);
        }

        $this->saveAudit(['manager' => $manager, 'worker' => $worker]);
        return $this->render('@App/companyAdmin/appraiseAdd.html.twig', [
            'company' => $this->getCompany(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * Company Admin appraise edit Action
     *
     * @param Request       $request
     * @param User          $manager
     * @param CompanyWorker $worker
     * @param Appraise      $appraise
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function appraiseEditAction(Request $request, User $manager, CompanyWorker $worker, Appraise $appraise)
    {
        $form = $this->createForm(AppraiseForm::class, $appraise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $editedAppraise = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($editedAppraise);
            $em->flush();

            $this->saveAudit(['manager' => $manager, 'worker' => $worker, 'apraise' => $appraise, 'data' => $editedAppraise]);
            return $this->redirectToRoute('app_company_admin_appraise_list', [
                'worker' => $worker->getId(),
                'manager' => $manager->getId(),
            ]);
        }

        $this->saveAudit(['manager' => $manager, 'worker' => $worker, 'apraise' => $appraise]);
        return $this->render('@App/companyAdmin/appraiseEdit.html.twig', [
            'form' => $form->createView(),
            'company' => $this->getCompany(),
            'manager' => $manager,
            'worker' => $worker,
        ]);
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
        $em = $this->getDoctrine()->getManager();
        $em->remove($appraise);
        $em->flush();

        $this->saveAudit(['manager' => $manager, 'worker' => $worker, 'apraise' => $appraise]);
        return $this->redirectToRoute('app_company_admin_appraise_list', [
           'worker' => $worker->getId(),
           'manager' => $manager->getId(),
        ]);
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
