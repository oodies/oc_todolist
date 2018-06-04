<?php
/**
 * This file is part of oc_todolist project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/05
 */

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\ContextTestTrait;

/**
 * Class UserControllerWebTest
 *
 * @package Tests\AppBundle\Controller
 */
class UserControllerWebTest extends WebTestCase
{
    /** *******************************
     *  TRAIT
     */
    use ContextTestTrait;


    /** *******************************
     *  TEST
     */

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     * overrides PHPUnit_Framework_TestCase
     *
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     */
    public function setUp()
    {
        $this->client = static::CreateClient();
        $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
    }

    /**
     * Feature: View the list of users
     *  Scenario: Nominal
     *      Given: admin authenticated
     *      When: I want to access to url "/users"
     *      Then: I am on "/users"
     *      And: I should see "List des utilisateurs"
     *
     * @throws \InvalidArgumentException
     * @throws \PHPUnit_Framework_AssertionFailedError
     * @throws \PHPUnit_Framework_Exception
     * @throws \RuntimeException
     */
    public function testViewListOfUserAction()
    {
        $this->withAuthenticatedAdmin();
        $crawler = $this->client->request('GET', '/users');
        $this->assertTrue(
            $this->client->getResponse()->isSuccessful(),
            sprintf('Status code : %s', $this->client->getResponse()->getStatusCode())
        );

        $this->assertContains('Liste des utilisateurs', $crawler->filter(".container h1")->text());
    }

    /**
     * Feature: Create a new user
     *  Scenario: Nominal
     *      Given: admin authenticated
     *      When: I am on "/users/create"
     *      And: I fill "user" with "usernameTest"
     *      And: I fill "password.first" with "12345"
     *      And: I fill "password.second" with "12345"
     *      And: I fill "email" with "email@mail.com"
     *      And: I select "roles" with "utilisateur"
     *      And: I submit form
     *      Then: I redirect "/users"
     *      And: I should see "alert success"
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function testCreate()
    {
        // Init database
        $this->fixtureDeleteUsernameTest();

        $this->withAuthenticatedAdmin();
        $crawler = $this->client->request('GET', '/users/create');
        /** @var \Symfony\Component\DomCrawler\Form $form */
        $form = $crawler->selectButton('Ajouter')->form();
        $form->setValues(
            [
                'user[username]'         => $this->getUsernameTest(),
                'user[password][first]'  => '12345',
                'user[password][second]' => '12345',
                'user[email]'            => 'email@mail.com',
                'user[roles]'            => 'ROLE_USER'
            ]
        );

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertEquals(1, $crawler->filter('div.alert.alert-success')->count());
    }

    /**
     * Feature: Edit an user
     *  Scenario: Nominal
     *      Given: admin authenticated
     *      When: I am on "/users/1/edit"
     *      And: I fill "user" with "usernameTest"
     *      And: I fill "password.first" with "6789"
     *      And: I fill "password.second" with "6789"
     *      And: I fill "email" with "email2@mail.com"
     *      And: I select "roles" with "utilisateur"
     *      And: I submit form
     *      Then: I redirect "/users"
     *      And: I should see "alert success"
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \LogicException
     */
    public function testEdit()
    {
        // Init database
        $idUser = $this->fixtureCreateUsernameTest();

        $this->withAuthenticatedAdmin();
        $crawler = $this->client->request('GET', sprintf('/users/%s/edit', $idUser));

        /** @var \Symfony\Component\DomCrawler\Form $form */
        $form = $crawler->selectButton('Modifier')->form();
        $form->setValues(
            [
                'user[username]'         => $this->getUsernameTest(),
                'user[password][first]'  => '6789',
                'user[password][second]' => '6789',
                'user[email]'            => 'email2@mail.com',
                'user[roles]'            => 'ROLE_ADMIN'
            ]
        );

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertEquals(1, $crawler->filter('div.alert.alert-success')->count());
    }
}
