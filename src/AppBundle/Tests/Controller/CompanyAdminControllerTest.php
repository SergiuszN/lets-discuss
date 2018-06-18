<?php
namespace AppBundle\Tests\Controller;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class CompanyAdminControllerTest extends WebTestCase
{
    /** @var Client */
    private $client = null;

    /** @var ContainerInterface */
    private $container;

    /** @var Router */
    private $router;

    /** @var EntityManager */
    private $manager;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
        $this->router = $this->container->get('router');
        $this->manager = $this->container->get('doctrine')->getManager();
    }

    public function testSecuredCompanyIndex()
    {
        $this->logInAdmin();
        $crawler = $this->client->request('GET', $this->router->generate('app_company_admin_homepage'));

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSame('Dashboard Here you can see general system statistics', $crawler->filter('h1')->text());
    }

    public function testSecuredCompanyAdminManagersList()
    {
        $this->logInAdmin();
        $crawler = $this->client->request('GET', $this->router->generate('app_company_admin_manager_list'));

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSame('Manager Lists Here you can see all added managers', $crawler->filter('h1')->text());
    }

    public function testUnSecuredCompanyAdminManagersList()
    {
        $this->client->request('GET', $this->router->generate('app_company_admin_manager_list'));
        $this->assertSame(Response::HTTP_FOUND , $this->client->getResponse()->getStatusCode());
    }

    public function testSecuredCompanyAdminManagerAddAction(){
        $this->logInAdmin();
        $crawler = $this->client->request('GET', $this->router->generate('app_company_admin_manager_add'));

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSame('Manager Add Create new manager', $crawler->filter('h1')->text());
    }

    public function testUnSecuredCompanyAdminManagerAddAction(){
        $this->client->request('GET', $this->router->generate('app_company_admin_manager_add'));
        $this->assertSame(Response::HTTP_FOUND , $this->client->getResponse()->getStatusCode());
    }

    public function testSecuredCompanyAdminManagerEditAction(){
        $this->logInAdmin();
        $crawler = $this->client->request('GET', $this->router->generate('app_company_admin_manager_edit', ['manager'=>24]));

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSame('Manager Edit Edit manager', $crawler->filter('h1')->text());
    }

    public function testUnSecuredCompanyAdminManagerEditAction(){
        $this->client->request('GET', $this->router->generate('app_company_admin_manager_edit', ['manager'=>24]));
        $this->assertSame(Response::HTTP_FOUND , $this->client->getResponse()->getStatusCode());
    }

    public function testSecuredManagerWorkersList()
    {
        $this->logInManager();
        $crawler = $this->client->request('GET', $this->router->generate('app_company_admin_worker_list', ['manager'=>24]));

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSame('Worker Lists Here you can see all added workers', $crawler->filter('h1')->text());
    }

    public function testUnSecuredManagerWorkersList()
    {
        $this->client->request('GET', $this->router->generate('app_company_admin_worker_list', ['manager'=>24]));
        $this->assertSame(Response::HTTP_FOUND , $this->client->getResponse()->getStatusCode());
    }

    public function testSecuredManagerWorkerAddAction()
    {
        $this->logInManager();
        $crawler = $this->client->request('GET', $this->router->generate('app_company_admin_worker_add', ['manager'=>24]));

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSame('Worker Add Create new worker', $crawler->filter('h1')->text());
    }

    public function testUnSecuredManagerWorkerAddAction()
    {
        $this->client->request('GET', $this->router->generate('app_company_admin_worker_add', ['manager'=>24]));
        $this->assertSame(Response::HTTP_FOUND , $this->client->getResponse()->getStatusCode());
    }

    public function testSecuredManagerWorkerEditAction()
    {
        $this->logInManager();
        $crawler = $this->client->request('GET', $this->router->generate('app_company_admin_worker_edit',
            ['manager'=>24, 'worker'=>5]));

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSame('Worker Edit Edit worker', $crawler->filter('h1')->text());
    }

    public function testUnSecuredManagerWorkerEditAction()
    {
        $this->client->request('GET', $this->router->generate('app_company_admin_worker_edit', ['manager'=>24, 'worker'=>5]));
        $this->assertSame(Response::HTTP_FOUND , $this->client->getResponse()->getStatusCode());
    }

    public function testSecuredManagerAppraiseListAction()
    {
        $this->logInManager();
        $crawler = $this->client->request('GET', $this->router->generate('app_company_admin_appraise_list',
            ['manager'=>24, 'worker'=>5]));

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSame('Appraise Lists Here you can see all added appraisals', $crawler->filter('h1')->text());
    }

    public function testUnSecuredManagerAppraiseListAction()
    {
        $this->client->request('GET', $this->router->generate('app_company_admin_appraise_list', ['manager'=>24, 'worker'=>5]));
        $this->assertSame(Response::HTTP_FOUND , $this->client->getResponse()->getStatusCode());
    }

    public function testSecuredManagerAppraiseAddAction()
    {
        $this->logInManager();
        $crawler = $this->client->request('GET', $this->router->generate('app_company_admin_appraise_add',
            ['manager'=>24, 'worker'=>5]));

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSame('Appraise Add Add new appraise', $crawler->filter('h1')->text());
    }

    public function testUnSecuredManagerAppraiseAddAction()
    {
        $this->client->request('GET', $this->router->generate('app_company_admin_appraise_add', ['manager'=>24, 'worker'=>5]));
        $this->assertSame(Response::HTTP_FOUND , $this->client->getResponse()->getStatusCode());
    }

    private function logIn($userId)
    {
        $session = $this->client->getContainer()->get('session');

        $firewall = 'main';

        $userRepository = $this->manager->getRepository(User::class);

        $token = new UsernamePasswordToken($userRepository->find($userId), null, $firewall, array('ROLE_COMPANY_ADMIN'));
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    private function logInAdmin()
    {
        $this->logIn($this->container->getParameter('test_id_user_company_admin'));
    }

    private function logInManager()
    {
        $this->logIn($this->container->getParameter('test_id_user_company_manager'));
    }
}