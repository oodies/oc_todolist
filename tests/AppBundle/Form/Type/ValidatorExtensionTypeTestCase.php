<?php
/**
 * This file is part of oc_todolist project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/05
 *
 * @see https://github.com/FriendsOfSymfony/FOSUserBundle/blob/master/Tests/Form/Type/ValidatorExtensionTypeTestCase.php
 */

namespace Tests\AppBundle\Form\Type;

use Symfony\Component\Form\Extension\Validator\Type\FormTypeValidatorExtension;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Class ValidatorExtensionTypeTestCase
 *
 * @package Tests\AppBundle\Form
 */
class ValidatorExtensionTypeTestCase extends TypeTestCase
{
    /**
     * @return array
     */
    protected function getTypeExtensions()
    {
        $validator = $this->getMockBuilder('Symfony\Component\Validator\Validator\ValidatorInterface')->getMock();
        $validator->method('validate')->will($this->returnValue(new ConstraintViolationList()));
        return array(
            new FormTypeValidatorExtension($validator),
        );
    }
}
