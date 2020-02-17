<?php

namespace App\ValueObject;

class NoteMetadata
{
    /**
     * @var string
     */
    private $page;

    /**
     * @var string
     */
    private $location;

    /**
     * @var string
     */
    private $date;

    public function __construct(string $metadata)
    {
        if (stristr($metadata, 'page')) {
            preg_match("/page (\d*-?\d*)/", $metadata, $output);
            $this->page = $output[1];
        }

        if (stristr($metadata, 'location')) {
            preg_match("/location (\d*-?\d*)/", $metadata, $output);
            $this->location = $output[1];
        }

        if (stristr($metadata, 'added')) {
            preg_match("/Added on (.*)/", $metadata, $output);
            $this->date = $output[1];
        }
    }

    /**
     * @return string
     */
    public function getPage(): ?string
    {
        return $this->page;
    }

    /**
     * @return string
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @return string
     */
    public function getDate(): ?string
    {
        return $this->date;
    }
}