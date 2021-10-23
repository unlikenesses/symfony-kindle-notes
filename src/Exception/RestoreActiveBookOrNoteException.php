<?php

namespace App\Exception;

class RestoreActiveBookOrNoteException extends \Exception
{
    protected $message = 'You can not restore a book or note that has not been soft-deleted';
}