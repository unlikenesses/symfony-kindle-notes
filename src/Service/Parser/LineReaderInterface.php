<?php

namespace App\Service\Parser;

use App\ValueObject\FileLine;
use App\Service\Parser\Actions\ActionInterface;

interface LineReaderInterface
{
    public function classifyLine(FileLine $line): ActionInterface;
}
