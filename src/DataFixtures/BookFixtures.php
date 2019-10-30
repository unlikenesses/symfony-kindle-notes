<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

class BookFixtures extends Fixture implements FixtureGroupInterface
{
    private $user;
    
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

    public function load(ObjectManager $manager)
    {
        $this->createUser($manager);
        $book = new Book();
        $book->setTitle('Test title')
            ->setTitleString('Test title')
            ->setAuthorFirstName('Fred')
            ->setAuthorLastName('Smith')
            ->setYear('1980')
            ->setUser($this->user);
        $manager->persist($book);

        $manager->flush();
    }
}
