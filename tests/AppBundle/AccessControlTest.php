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
 * Class AccessControlTest
 *
 * @package Tests\AppBundle
 */
class AccessControlTest extends WebTestCase
{
    /** *******************************
     *  TRAIT
     */
    use ContextTestTrait;


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
     * @dataProvider routeForTest
     */
    public function testListUser($set, $url, $user, $response)
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
                $this->assertTrue($this->client->getResponse()->isServerError());
                break;
            case "redirect":
                $this->assertTrue($this->client->getResponse()->isRedirect());
                break;
            case "forbidden":
                $this->assertTrue($this->client->getResponse()->isForbidden());
                break;
            case "ok":
                $this->assertTrue($this->client->getResponse()->isOk());
                break;
        }
    }

    /**
     * @return array
     */
    public function routeForTest()
    {
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
            ['12', '/tasks/1/edit', 'noAuth', 'signIn'],
            ['13', '/tasks/1/edit', 'userAuth', 'ok'],
            ['14', '/tasks/1/edit', 'adminAuth', 'ok'],
            ['15', '/tasks/1/toggle', 'noAuth', 'signIn'],
            ['16', '/tasks/1/toggle', 'userAuth', 'ok'],
            ['17', '/tasks/1/toggle', 'adminAuth', 'ok'],
            ['18', '/tasks/1/delete', 'noAuth', 'signIn'],
            ['19', '/tasks/1/delete', 'userAuth', 'ok'],
            ['20', '/tasks/1/delete', 'adminAuth', 'ok'],
            ['21', '/users', 'noAuth', 'signIn'],
            ['22', '/users', 'userAuth', 'forbidden'],
            ['23', '/users', 'adminAuth', 'ok'],
            ['24', '/users/create', 'noAuth', 'signIn'],
            ['25', '/users/create', 'userAuth', 'forbidden'],
            ['26', '/users/create', 'adminAuth', 'ok']
        ];
    }
}
