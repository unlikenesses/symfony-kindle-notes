<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public static function getGroups(): array
    {
        return ['testing'];
    }

    private function createUser(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setPassword('test');
        $manager->persist($user);
        $this->user = $user;
    }

    public function load(ObjectManager $manager): void
    {
        $this->createUser($manager);
        $manager->flush();
    }
}
