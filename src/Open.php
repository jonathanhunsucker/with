<?php

namespace JonathanHunsucker\With;

class Open implements Context
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $mode;

    /**
     * @var resource
     */
    private $handle;

    public function __construct(string $filename, string $mode)
    {
        $this->filename = $filename;
        $this->mode = $mode;
    }

    public function enter()
    {
        $handle = fopen($this->filename, $this->mode);
        if ($handle === false) {
            throw new Exception("Failed to open file handle `$this->filename`");
        }

        return $this->handle = $handle;
    }

    public function exit()
    {
        fclose($this->handle);
    }
}
