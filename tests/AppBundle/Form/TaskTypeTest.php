<?php
/**
 * This file is part of oc_todolist project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/05
 */

namespace Tests\AppBundle\Form;

use AppBundle\Entity\Task;
use AppBundle\Form\TaskType;
use Tests\AppBundle\Form\Type\ValidatorExtensionTypeTestCase;

/**
 * Class TaskTypeTest
 *
 * @package Tests\AppBundle\Form
 */
class TaskTypeTest extends ValidatorExtensionTypeTestCase
{
    public function testSubmit()
    {
        $task = new Task();

        $form = $this->factory->create(TaskType::class, $task);
        $formData = [
            'title' => 'titleTest',
            'content' => 'contentTest'
        ];
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertSame($task, $form->getData());
        $this->assertSame('titleTest', $task->getTitle());
        $this->assertSame('contentTest', $task->getContent());
    }
}
