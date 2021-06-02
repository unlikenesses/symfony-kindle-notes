<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * @var User
     */
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

    public function load(ObjectManager $manager): void
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
