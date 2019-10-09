<?php

namespace JonathanHunsucker\With;

class ErrorHandler implements Context
{
    public function __construct(\closure $error_handler)
    {
        $this->error_handler = $error_handler;
    }

    public function enter()
    {
        $error_handler = $this->error_handler;
        $previous_handler = null;

        $wrapped = function (
            int $errno,
            string $errstr,
            string $errfile = null,
            int $errline = null,
            array $errcontext = null
        ) use ($error_handler, &$previous_handler) {
            $error_handler($previous_handler, $errno, $errstr, $errfile, $errline, $errcontext);
        };

        $previous_handler = set_error_handler($wrapped);
    }

    public function exit()
    {
        restore_error_handler();
    }
}
