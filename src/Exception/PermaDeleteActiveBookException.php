<?php

namespace App\Exception;

class PermaDeleteActiveBookException extends \Exception
{
    protected $message = 'You can not perma-delete a book that has not been soft-deleted';
}