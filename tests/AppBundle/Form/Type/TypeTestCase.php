<?php
/**
 * This file is part of oc_todolist project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/05
 *
 * @see https://github.com/FriendsOfSymfony/FOSUserBundle/blob/master/Tests/Form/Type/TypeTestCase.php
 */

namespace Tests\AppBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Test\TypeTestCase as BaseTypeTestCase;

/**
 * Class TypeTestCase
 *
 * @package Tests\AppBundle\Form
 */
abstract class TypeTestCase extends BaseTypeTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->factory = Forms::createFormFactoryBuilder()
                              ->addTypes($this->getTypes())
                              ->addExtensions($this->getExtensions())
                              ->addTypeExtensions($this->getTypeExtensions())
                              ->getFormFactory();
        $this->builder = new FormBuilder(null, null, $this->dispatcher, $this->factory);
    }

    /**
     * @return array
     */
    protected function getTypeExtensions()
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getTypes()
    {
        return [];
    }
}
