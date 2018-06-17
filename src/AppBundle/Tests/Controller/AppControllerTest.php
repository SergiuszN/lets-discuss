<?php
namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    public function testCheckIndexRedirectToLogin()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertRegExp('/\/login$/', $client->getResponse()->headers->get('location'), 'Index not redirect to login');
    }
}