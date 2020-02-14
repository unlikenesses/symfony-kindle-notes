<?php

namespace App\ValueObject;

class Author
{
    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    public function __construct(string $firstName, string $lastName)
    {
        $this->firstName = trim($firstName);
        $this->lastName = trim($lastName);
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }
}