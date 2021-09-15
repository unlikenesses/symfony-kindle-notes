<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Note;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    protected const TEST_DATA = [
        'books' => [
            [
                'title' => 'Test title',
                'author_first_name' => 'Jim',
                'author_last_name' => 'Smith',
                'year' => 1980,
                'notes' => [
                    [
                        'page' => 1,
                        'note' => 'this is a note',
                        'type' => 1,
                        'date' => '2021-09-23',
                    ],
                    [
                        'page' => 3,
                        'note' => 'this is another note',
                        'type' => 1,
                        'date' => '2021-09-28',
                    ]
                ],
            ],
            [
                'title' => 'Another test title',
                'author_first_name' => 'Mary',
                'author_last_name' => 'Jones',
                'year' => 1990,
                'notes' => [
                    [
                        'page' => 11,
                        'note' => 'hello',
                        'type' => 1,
                        'date' => '2020-03-13',
                    ],
                    [
                        'page' => 5,
                        'note' => 'goodbye',
                        'type' => 1,
                        'date' => '2020-03-11',
                    ]
                ],
            ],
        ],
    ];

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
        foreach (self::TEST_DATA['books'] as $bookData) {
            $book = new Book();
            $book->setTitle($bookData['title'])
                ->setTitleString($bookData['title'])
                ->setAuthorFirstName($bookData['author_first_name'])
                ->setAuthorLastName($bookData['author_last_name'])
                ->setYear($bookData['year'])
                ->setUser($this->user);
            foreach ($bookData['notes'] as $noteData) {
                $note = new Note();
                $note->setBook($book);
                $note->setType($noteData['type']);
                $note->setPage($noteData['page']);
                $note->setNote($noteData['note']);
                $note->setDate(new DateTime($noteData['date']));
                $note->setHash($this->calculateNoteHash($noteData));
                $manager->persist($note);
            }
            $manager->persist($book);
        }

        $manager->flush();
    }

    protected function calculateNoteHash(array $note): string
    {
        $page = $note['page'] ?? '';
        $location = $note['location'] ?? '';
        $date = $note['date'] ?? '';
        $date = new DateTime($date);
        $noteText = $note['note'];

        return md5($page.$location.$date->format('U').$noteText);
    }
}
