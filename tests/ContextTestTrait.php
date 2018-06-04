<?php
/**
 * This file is part of oc_todolist project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/05
 *
 * @see   https://symfony.com/doc/3.1/testing/simulating_authentication.html
 */

namespace Tests;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Trait ContextTestTrait
 *
 * @package Tests
 */
trait ContextTestTrait
{
    /** *******************************
     *  PROPERTIES
     */

    /** @var \Symfony\Bundle\FrameworkBundle\Client|null $client */
    private $client = null;

    /** @var \Doctrine\ORM\EntityManagerInterface|null $em */
    private $em = null;

    /** @var  Application $application */
    protected static $application;


    /** *******************************
     *  METHODS
     */

    /**
     * @return Application
     */
    protected static function getApplication()
    {
        if (null === self::$application) {
            $client = static::createClient();

            self::$application = new Application($client->getKernel());
            self::$application->setAutoExit(false);
        }

        return self::$application;
    }

    /**
     * @example self::runCommand('doctrine:fixtures:load --env=test --no-interaction --purge-with-truncate');
     *
     * @param $command
     *
     * @return int
     * @throws \Exception
     */
    protected static function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);

        return self::getApplication()->run(new StringInput($command));
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     */
    private function withAuthenticatedAdmin()
    {
        /** @var \Symfony\Component\HttpFoundation\Session\Session $session */
        $session = $this->client->getContainer()->get('session');
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(['username' => 'admin']);

        // the firewall context (defaults to the firewall name)
        $firewall = 'main';

        $token = new UsernamePasswordToken($user, '12345', $firewall, ['ROLE_ADMIN']);
        $session->set('_security_' . $firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     */
    private function withAuthenticatedUser()
    {
        /** @var \Symfony\Component\HttpFoundation\Session\Session $session */
        $session = $this->client->getContainer()->get('session');
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(['username' => 'user']);

        // the firewall context (defaults to the firewall name)
        $firewall = 'main';

        $token = new UsernamePasswordToken($user, '12345', $firewall, ['ROLE_USER']);
        $session->set('_security_' . $firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    /**
     *  Create an user test in database
     *
     * @return int identifier usernameTest
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    private function fixtureCreateUsernameTest()
    {
        /** @var User $usernameTest */
        $usernameTest = $this->em->getRepository('AppBundle:User')->findOneBy(['username' => $this->getUsernameTest()]);
        if (null === $usernameTest) {
            $user = new User();
            $user->setUsername($this->getUsernameTest());
            $user->setEmail('email@mail.com');
            $user->setRoles([User::ROLE_DEFAULT]);
            $password = $this->client->getContainer()
                                     ->get('security.password_encoder')
                                     ->encodePassword(
                                         $user,
                                         '12345'
                                     );
            $user->setPassword($password);

            $this->em->persist($user);
            $this->em->flush();
        }

        return $usernameTest->getId();
    }

    /**
     * Delete the user test in database
     */
    private function fixtureDeleteUsernameTest()
    {
        $usernameTest = $this->em->getRepository('AppBundle:User')->findOneBy(['username' => $this->getUsernameTest()]);
        if ($usernameTest) {
            $this->em->remove($usernameTest);
            $this->em->flush();
        }
    }

    /**
     * Create a task test
     */
    private function fixtureCreateTaskTest()
    {
        /** @var User $usernameTest */
        $taskTest = $this->em->getRepository('AppBundle:Task')->findOneBy(['title' => $this->getNameOfTaskTest()]);
        if (null === $taskTest) {
            $user = $this->em->getRepository('AppBundle:User')->findOneBy(['username' => 'user']);

            $task = new Task();
            $task->setTitle($this->getNameOfTaskTest());
            $task->setContent(
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse rutrum justo nec augue eleifend, in fermentum lacus efficitur. In finibus neque a vestibulum scelerisque. Ut at mauris nec tortor aliquet maximus. Cras id sem orci. Cras vel ultricies urna, ac varius nisi. Quisque tristique gravida molestie. Cras non convallis sapien. Phasellus nulla ligula, tristique nec lorem at, mattis sodales diam. Morbi tincidunt a metus ut semper. Sed non rhoncus metus. Mauris in viverra purus. Nulla facilisi. Aliquam porttitor et arcu a consequat. Donec blandit ligula quis nisl consequat condimentum.'
            );
            $task->setAuthor($user);
            $this->em->persist($task);
            $this->em->flush();
        }

        return $taskTest->getId();
    }

    /**
     * Delete a task test
     */
    private function fixtureDeleteTaskTest()
    {
        $taskTest = $this->em->getRepository('AppBundle:Task')->findOneBy(['title' => $this->getNameOfTaskTest()]);
        if ($taskTest) {
            $this->em->remove($taskTest);
            $this->em->flush();
        }
    }

    /**
     * Username test
     *
     * @return string
     */
    private function getUsernameTest()
    {
        return 'usernameTest';
    }

    /**
     * an name of task test
     *
     * @return string
     */
    private function getNameOfTaskTest()
    {
        return 'nameOfTaskTest';
    }

    private function truncateEntities()
    {
        $purger = new ORMPurger($this->em);
        $purger->purge();
    }
}

