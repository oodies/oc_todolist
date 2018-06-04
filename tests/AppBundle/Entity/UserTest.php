<?php
/**
 * This file is part of oc_todolist project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/05
 */

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\User;

/**
 * Class UserTest
 *
 * @package Tests\AppBundle\Entity
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var User $user
     */
    protected $user;

    public function setUp()
    {
        $this->user = new User();
    }

    public function testId()
    {
        $this->assertNull($this->user->getId());
    }

    public function testUsername()
    {
        $this->assertNull($this->user->getUsername());

        $this->user->setUsername('tony');
        $this->assertEquals('tony', $this->user->getUsername());
    }

    public function testSalt()
    {
        $this->assertNull($this->user->getSalt());
    }

    public function testPassword()
    {
        $this->assertNull($this->user->getPassword());

        $this->user->setPassword('testPassword');
        $this->assertEquals('testPassword', $this->user->getPassword());
    }

    public function testEmail()
    {
        $this->user->setEmail('testEmail');

        $this->assertEquals('testEmail', $this->user->getEmail());
    }

    public function testRoles()
    {
        $this->assertEquals((array)User::ROLE_DEFAULT , $this->user->getRoles());

        $this->user->setRoles(['ROLE_X']);
        $this->assertEquals(['ROLE_X'], $this->user->getRoles());
    }
}
