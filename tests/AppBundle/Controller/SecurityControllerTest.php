<?php
/**
 * This file is part of oc_todolist project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/05
 */

namespace Tests\AppBundle\Controller;

use Tests\ContextTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class SecurityControllerTest
 *
 * @package Tests\AppBundle\Controller
 */
class SecurityControllerTest extends WebTestCase
{
    /** *******************************
     *  TRAIT
     */
    use ContextTestTrait;


    /** *******************************
     *  TESTS
     */

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     * overrides PHPUnit_Framework_TestCase
     */
    public function setUp()
    {
        $this->client = static::CreateClient();
    }


    /**
     * Feature: Login action with authenticated user
     *  Scenario: nominal
     *      Given: user authenticated
     *      When: I am on "/login"
     *      Then: I redirect to "/"
     *
     * @throws \PHPUnit_Framework_AssertionFailedError
     */
    public function testLoginWithAuthenticatedUser()
    {
        $this->withAuthenticatedUser();
        $this->client->request('GET', '/login');
        $this->assertTrue($this->client->getResponse()->isRedirect('/'));
    }

    /**
     * Feature: Logout action
     *  Scenario #1: Nominal, with user authenticated
     *      Given: user authenticated
     *      When: I am on "/logout"
     *      Then: I redirect to "/login"
     *  Scenario #2: with user unauthenticated
     *      When: I am on "/logout"
     *      Then: I redirect to "/login"
     *
     * @throws \LogicException
     * @throws \PHPUnit_Framework_Exception
     */
    public function testLogout()
    {
        // Scenario #1
        $this->withAuthenticatedUser();
        $this->client->request('GET', '/logout');
        $this->client->followRedirect();
        $this->assertRegExp('/\/login$/', $this->client->getResponse()->headers->get('location'));
        // Scenario 2
        $this->client->request('GET', '/logout');
        $this->client->followRedirect();
        $this->assertRegExp('/\/login$/', $this->client->getResponse()->headers->get('location'));
    }

    /**
     * Feature: Sign in as a user
     *  Scenario : Nominal, with valid credentials
     *      Given: I am on "/login"
     *      When: I fill "_username" with "user"
     *      And: I fill "_password" with "12345"
     *      And: I press submit
     *      Then: I redirect to "/"
     *      And: I should see "Bienvenue sur Todo List"
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit_Framework_Exception
     * @throws \RuntimeException
     */
    public function testSignInAsUserWithValidCredentials()
    {
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();
        $form->setValues(
            [
                '_username' => 'user',
                '_password' => '12345'
            ]
        );
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertContains(
            'Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !',
            $crawler->filter('h1')->text()
        );
    }

    /**
     * Feature: Sign in as a user
     *  Scenario  With invalid credentials
     *      Given: I am on "/login"
     *      When: I fill "_username" with "qwerty"
     *      And: I fill "_password" with "password"
     *      And: I press submit
     *      Then: I redirect to "/login"
     *      And: I should see "Invalid credentials"
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function testSignInAsUserWithInvalidCredentials()
    {
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();
        $form->setValues(
            [
                '_username' => 'qwerty',
                '_password' => 'password'
            ]
        );
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertSame(1, $crawler->filter('div.alert.alert-danger')->count());
    }

}
