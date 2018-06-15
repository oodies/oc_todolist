<?php

/*
 * This file is part of oc_todolist project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/05
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class TaskFixtures.
 */
class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }

    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $reference = [];
        for ($i = 1; $i <= 20; ++$i) {
            $reference[] = "username_${i}";
        }
        $reference[] = 'user';
        $reference[] = 'admin';

        for ($i = 1; $i <= 200; ++$i) {
            $task = new Task();
            $task->setTitle("task-${i}");
            $task->setContent(
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse rutrum justo nec augue eleifend, in fermentum lacus efficitur. In finibus neque a vestibulum scelerisque. Ut at mauris nec tortor aliquet maximus. Cras id sem orci. Cras vel ultricies urna, ac varius nisi. Quisque tristique gravida molestie. Cras non convallis sapien. Phasellus nulla ligula, tristique nec lorem at, mattis sodales diam. Morbi tincidunt a metus ut semper. Sed non rhoncus metus. Mauris in viverra purus. Nulla facilisi. Aliquam porttitor et arcu a consequat. Donec blandit ligula quis nisl consequat condimentum.'
            );
            $task->toggle(rand(0, 1));
            if (rand(0, 1)) {
                $task->setAuthor($this->getReference($reference[array_rand($reference, 1)]));
            }

            $manager->persist($task);
            // Flush every 100 entities and clear manager
            if (0 === $i % 100) {
                $manager->flush();
                $manager->clear('AppBundle\Entity\Task');
            }
        }
    }
}
