<?php

namespace App\Api;

use App\Entity\Book;

class NoteApiModel
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $date;

    /**
     * @var string
     */
    public $page;

    /**
     * @var string
     */
    public $location;

    /**
     * @var string
     */
    public $note;

    /**
     * @var int
     */
    public $type;

    /**
     * @var string
     */
    public $source;

    /**
     *
     */
    public $tags;
}
