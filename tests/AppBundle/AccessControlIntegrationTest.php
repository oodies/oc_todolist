<?php
/**
 * This file is part of oc_todolist project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/05
 */

namespace Tests\AppBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\ContextTestTrait;

/**
 * Class AccessControlIntegrationTest
 *
 * @package Tests\AppBundle
 */
class AccessControlIntegrationTest extends WebTestCase
{
    /** *******************************
     *  TRAIT
     */
    use ContextTestTrait;


    protected $idTask = null;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     * overrides PHPUnit_Framework_TestCase
     */
    public function setUp()
    {
        $this->client = static::CreateClient();
        $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
        $this->idTask = (string)$this->fixtureCreateTaskTest();
    }


    /**
     * @dataProvider routeForTest
     *
     * @param string $numTest
     * @param string $url
     * @param string $user
     * @param string $response
     *
     * @throws \InvalidArgumentException
     * @throws \PHPUnit_Framework_AssertionFailedError
     * @throws \PHPUnit_Framework_Exception
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \UnexpectedValueException
     */
    public function testListUser($numTest, $url, $user, $response)
    {
        switch ($user) {
            case "userAuth":
                $this->withAuthenticatedUser();
                break;
            case "adminAuth":
                $this->withAuthenticatedAdmin();
                break;
            case "noAuth":
                break;
        }

        $this->client->request('GET', $url);

        switch ($response) {
            case "signIn":
                $this->assertRegExp('/\/login$/', $this->client->getResponse()->headers->get('location'));
                break;
            case "serverError":
                $this->assertTrue(
                    $this->client->getResponse()->isServerError(),
                    'Error : ' . $this->client->getResponse()->getStatusCode()
                );
                break;
            case "redirect":
                $this->assertTrue(
                    $this->client->getResponse()->isRedirect(),
                    'Error : ' . $this->client->getResponse()->getStatusCode()
                );
                break;
            case "forbidden":
                $this->assertTrue(
                    $this->client->getResponse()->isForbidden(),
                    'Error : ' . $this->client->getResponse()->getStatusCode()
                );
                break;
            case "ok":
                $this->assertTrue(
                    $this->client->getResponse()->isOk(), 'Error : ' . $this->client->getResponse()->getStatusCode()
                );
                break;
        }
    }

    /**
     * @return array
     */
    public function routeForTest()
    {
        // STUB go to feature
        $idTask = '101';

        return [
            ['0', '/', 'noAuth', 'signIn'],
            ['1', '/', 'userAuth', 'ok'],
            ['2', '/', 'adminAuth', 'ok'],
            ['3', '/login', 'noAuth', 'ok'],
            ['4', '/login', 'userAuth', 'ok'],
            ['5', '/login', 'adminAuth', 'ok'],
            ['6', '/tasks', 'noAuth', 'signIn'],
            ['7', '/tasks', 'userAuth', 'ok'],
            ['8', '/tasks', 'adminAuth', 'ok'],
            ['9', '/tasks/create', 'noAuth', 'signIn'],
            ['10', '/tasks/create', 'userAuth', 'ok'],
            ['11', '/tasks/create', 'adminAuth', 'ok'],
            ['12', sprintf('/tasks/%s/edit', $idTask), 'noAuth', 'signIn'],
            ['13', sprintf('/tasks/%s/edit', $idTask), 'userAuth', 'ok'],
            ['14', sprintf('/tasks/%s/edit', $idTask), 'adminAuth', 'ok'],
            ['15', sprintf('/tasks/%s/toggle', $idTask), 'noAuth', 'signIn'],
            ['16', sprintf('/tasks/%s/toggle', $idTask), 'userAuth', 'redirect'],
            ['17', sprintf('/tasks/%s/toggle', $idTask), 'adminAuth', 'redirect'],
            ['18', sprintf('/tasks/%s/toggle', $idTask), 'noAuth', 'signIn'],
            ['19', sprintf('/tasks/%s/delete', $idTask), 'userAuth', 'redirect'],
            ['20', '/users', 'noAuth', 'signIn'],
            ['21', '/users', 'userAuth', 'forbidden'],
            ['22', '/users', 'adminAuth', 'ok'],
            ['23', '/users/create', 'noAuth', 'signIn'],
            ['24', '/users/create', 'userAuth', 'forbidden'],
            ['25', '/users/create', 'adminAuth', 'ok']
        ];
    }
}
