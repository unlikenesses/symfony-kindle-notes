<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

class BookFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['testing'];
    }

    public function load(ObjectManager $manager)
    {
        $book = new Book();
        $book->setTitle('Test title')
            ->setTitleString('Test title')
            ->setAuthorFirstName('Fred')
            ->setAuthorLastName('Smith')
            ->setYear('1980');
        $manager->persist($book);

        $manager->flush();
    }
}
