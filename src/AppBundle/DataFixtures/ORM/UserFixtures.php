<?php

/*
 * This file is part of oc_todolist project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/05
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

/**
 * Class UserFixtures.
 */
class UserFixtures extends Fixture implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var UserPasswordEncoder
     */
    protected $encoder;

    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager
     *
     * @throws \BadMethodCallException
     */
    public function load(ObjectManager $manager)
    {
        // Specific user with roles ROLE_USER
        $user = new User();
        $user->setUsername('user');
        $user->setEmail('user@mail.com');
        $user->setPassword($this->encoder->encodePassword($user, '12345'));
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);
        $this->addReference('user', $user);
        unset($user);

        // Specific user with roles ROLE_ADMIN
        $user = new User();
        $user->setUsername('admin');
        $user->setEmail('admin@mail.com');
        $user->setPassword($this->encoder->encodePassword($user, '12345'));
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);
        $this->addReference('admin', $user);
        unset($user);

        $manager->flush();

        // other user with roles ROLE_USER by default
        for ($i = 1; $i <= 20; ++$i) {
            $user = new User();
            $user->setUsername("username_${i}");
            $user->setEmail("username_${i}@mail.com");
            $user->setPassword($this->encoder->encodePassword($user, '12345'));

            $manager->persist($user);
            $this->addReference("username_${i}", $user);
            unset($user);

            // Flush every 10 entities and clear manager
            if (0 === $i % 10) {
                $manager->flush();
                $manager->clear('AppBundle\Entity\User');
            }
        }
    }

    /**
     * @param null|ContainerInterface $container
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->encoder = $this->container->get('security.password_encoder');
    }
}
