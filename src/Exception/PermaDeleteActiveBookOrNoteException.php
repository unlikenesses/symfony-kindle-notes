<?php

namespace App\Exception;

class PermaDeleteActiveBookOrNoteException extends \Exception
{
    protected $message = 'You can not perma-delete a book or note that has not been soft-deleted';
}