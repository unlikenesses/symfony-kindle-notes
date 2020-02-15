<?php

namespace App\ValueObject;

class FileLine
{
    /**
     * @var string
     */
    private $line;

    public function __construct(string $line)
    {
        $this->line = trim($this->removeBOM($line));
    }

    private function removeBOM(string $line): string
    {
        return preg_replace("/^\xEF\xBB\xBF/", '', $line);
    }

    public function getLine(): string
    {
        return $this->line;
    }

    public function equals(string $string): bool
    {
        return $this->line === $string;
    }

    public function contains(string $needle): bool
    {
        return stristr($this->line, $needle);
    }

    public function isEmpty(): bool
    {
        return strlen($this->line) <= 0;
    }
}