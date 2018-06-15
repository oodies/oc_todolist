<?php
/**
 * This file is part of oc_todolist project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/05
 */

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * Class TaskTest
 *
 * @package Tests\AppBundle\Entity
 */
class TaskTest extends TestCase
{
    /**
     * @var Task $task
     */
    protected $task;

    public function setUp()
    {
        $this->task = new Task();
    }

    public function testId()
    {
        $this->assertNull($this->task->getId());
    }

    public function testCreateAt()
    {
        $this->assertInstanceOf('\DateTime', $this->task->getCreatedAt());

        $date = new \DateTime();
        $this->task->setCreatedAt($date);
        $this->assertEquals($date, $this->task->getCreatedAt());
    }

    public function testTitle()
    {
        $this->assertNull($this->task->getTitle());

        $this->task->setTitle('testTitle');
        $this->assertEquals('testTitle', $this->task->getTitle());
    }


    public function testContent()
    {
        $this->assertNull($this->task->getContent());

        $this->task->setContent('testContent');
        $this->assertEquals('testContent', $this->task->getContent());
    }

    public function testAuthor()
    {
        $this->assertNull($this->task->getAuthor());

        $author = new User();
        $this->task->setAuthor($author);
        $this->assertEquals($author, $this->task->getAuthor());
    }

    public function testIsDone()
    {
        $this->assertEquals(false, $this->task->isDone());

        $this->task->toggle(true);
        $this->assertEquals(true, $this->task->isDone());

        $this->task->toggle(false);
        $this->assertEquals(false, $this->task->isDone());
    }
}
