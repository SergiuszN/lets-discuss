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