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
 * Class DefaultControllerTest
 *
 * @package Tests\AppBundle\Controller
 */
class DefaultControllerTest extends WebTestCase
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
     * @throws \InvalidArgumentException
     * @throws \PHPUnit_Framework_Exception
     * @throws \RuntimeException
     */
    public function testViewIndex()
    {
        // Scenario #1
        $this->client->request('GET', '/');
        $this->assertTrue($this->client->getResponse()->isRedirection());

        // Scenario #2
        $this->withAuthenticatedUser();
        $crawler = $this->client->request('GET', '/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertContains('Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !', $crawler->filter('h1')->text());
    }
}
