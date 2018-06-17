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

class SuperAdminControllerTest extends WebTestCase
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
        $this->logIn();
        $crawler = $this->client->request('GET', $this->router->generate('app_super_admin_homepage'));

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSame('Dashboard Here you can see general system statistics', $crawler->filter('h1')->text());
    }

    public function testSecuredAdminCompanyList()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', $this->router->generate('app_super_admin_company_list'));

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSame('Company Lists Here you can see all added companies', $crawler->filter('h1')->text());
    }

    public function testUnSecuredAdminCompanyList()
    {
        $this->client->request('GET', $this->router->generate('app_super_admin_company_list'));
        $this->assertSame(Response::HTTP_FOUND , $this->client->getResponse()->getStatusCode());
    }

    public function testSecuredAdminCompanyAddAction(){
        $this->logIn();
        $crawler = $this->client->request('GET', $this->router->generate('app_super_admin_company_add'));

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSame('Company Add Create new company', $crawler->filter('h1')->text());
    }

    public function testUnSecuredAdminCompanyAddAction(){
        $this->client->request('GET', $this->router->generate('app_super_admin_company_add'));
        $this->assertSame(Response::HTTP_FOUND , $this->client->getResponse()->getStatusCode());
    }


    private function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        $firewall = 'main';

        $userRepository = $this->manager->getRepository(User::class);

        $token = new UsernamePasswordToken($userRepository->find($this->container->getParameter('test_id_user_super_admin')), null, $firewall, array('ROLE_SUPER_ADMIN'));
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}