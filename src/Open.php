<?php

namespace JonathanHunsucker\With;

class Open implements Context
{
    private $filename;
    private $mode;

    public function __construct(string $filename, string $mode)
    {
        $this->filename = $filename;
        $this->mode = $mode;
    }

    public function enter()
    {
        return $this->handle = fopen($this->filename, $this->mode);
    }

    public function exit()
    {
        fclose($this->handle);
    }
}
