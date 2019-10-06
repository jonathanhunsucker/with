<?php

namespace JonathanHunsucker\With;

class Timer implements Context
{
    private $start;

    private $end;

    public function enter()
    {
        $this->start = microtime(true);
    }

    public function exit()
    {
        $this->end = microtime(true);
    }

    public function elapsed()
    {
        return $this->end - $this->start;
    }
}

