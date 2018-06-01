<?php
/**
 * This file is part of oc_todolist project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/05
 */

namespace Tests\AppBundle\Form;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Tests\AppBundle\Form\Type\ValidatorExtensionTypeTestCase;

/**
 * Class UserTypeTest
 *
 * @package Tests\AppBundle\Form
 */
class UserTypeTest extends ValidatorExtensionTypeTestCase
{
    public function testSubmit()
    {
        $user = new User();

        $form = $this->factory->create(UserType::class, $user);
        $formData = [
            'username' => 'usernameTest',
            'password' => [
                'first'  => 'passwordTest',
                'second' => 'passwordTest'
            ],
            'email'     => 'mail@mail.com',
            'roles'    => User::ROLE_DEFAULT
        ];
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertSame($user, $form->getData());
        $this->assertSame('usernameTest', $user->getUsername());
        $this->assertSame('passwordTest', $user->getPassword());
        $this->assertSame('mail@mail.com', $user->getEmail());
    }
}
