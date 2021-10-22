<?php

namespace App\Exception;

class WrongOwnerException extends \Exception
{
    protected $message = 'Wrong owner!';
}