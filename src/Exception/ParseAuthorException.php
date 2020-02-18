<?php

namespace App\Exception;

class ParseAuthorException extends \Exception
{
    protected $message = 'There was a problem parsing the author string.';
}