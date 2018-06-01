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
 * Class TaskControllerTest
 *
 * @package Tests\AppBundle\Controller
 */
class TaskControllerTest extends WebTestCase
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
        $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
    }

    /**
     * Feature: View the list of tasks
     *  Scenario: normal
     *      Given: user authenticated
     *      When: I want access to the URL "/tasks"
     *      Then: I am redirected to the page "Tasks"
     *      And: I should see a link "Créer une tâche"
     */
    public function testViewTheListOfTask()
    {
        $this->withAuthenticatedUser();
        $crawler = $this->client->request('GET', '/tasks');

        $this->assertTrue($this->client->getResponse()->isSuccessful(), $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->selectLink('Créer une tâche')->count());
    }

    /**
     * Feature: Create a task
     *   Scenario: nominal
     *      Given: user authenticated
     *      And: I am on "/tasks/create"
     *      When: I fill "title" with "a new task"
     *      And: I fill 'content" with "a comment task"
     *      And: I press submit
     *      Then: I redirect to "/tasks"
     *      And: I should see "alert success"
     */
    public function testCreate()
    {
        // Init database
        $this->fixtureDeleteTaskTest();

        $this->withAuthenticatedUser();
        /** @var \Symfony\Component\DomCrawler\Crawler $crawler */
        $crawler = $this->client->request('GET', '/tasks/create');
        /** @var \Symfony\Component\DomCrawler\Form $form */
        $form = $crawler->selectButton('Ajouter')->form();
        $form->setValues(
            [
                'task[title]'   => $this->getNameOfTaskTest(),
                'task[content]' => 'a comment task'
            ]
        );
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertEquals(1, $crawler->filter('div.alert.alert-success')->count());
    }

    /**
     *
     */
    public function testEdit()
    {
        // Init Database
        $idTask = $this->fixtureCreateTaskTest();

        $this->withAuthenticatedUser();
        $crawler = $this->client->request('GET', sprintf('tasks/%s/edit', $idTask));

        /** @var \Symfony\Component\DomCrawler\Form $form */
        $form = $crawler->selectButton('Modifier')->form();
        $form->setValues(
            [
                'task[title]'   => $this->getNameOfTaskTest(),
                'task[content]' => 'new content'
            ]
        );
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertEquals(1, $crawler->filter('div.alert.alert-success')->count());
    }

    /**
     * Feature: Delete a task
     *   Scenario: nominal
     *      Given: user authenticated
     *      And: I am on "/tasks"
     *      And: I see button "supprimer"
     *      When: I click "Supprimer"
     *      Then: I redirect to "/tasks"
     *      And: I should see "alert success"
     */
    public function testDelete()
    {
        // Init Database
        $idTask = $this->fixtureCreateTaskTest();

        $this->withAuthenticatedUser();
        $this->client->request('GET', sprintf('/tasks/%s/delete', $idTask));
        $crawler = $this->client->followRedirect();

        $this->assertEquals(1, $crawler->filter('div.alert.alert-success')->count());
    }
}
