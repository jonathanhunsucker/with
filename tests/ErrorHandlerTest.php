<?php

namespace JonathanHunsucker\With\Tests;

use PHPUnit\Framework\TestCase;

use function JonathanHunsucker\With\with;

use JonathanHunsucker\With\ErrorHandler;

class ErrorHandlerTest extends TestCase
{
    public function test(): void
    {
        $handled_error = new \stdClass();

        with(new ErrorHandler(function ($previous_handler, $error_number, $error_string) use (&$handled_error) {
            $handled_error->error_string = $error_string;
            $handled_error->was_called = true;

            return false;
        }))->do(function () {
            $list = [];
            $list["key that does not exist"];
        });

        $this->assertTrue($handled_error->was_called);
        $this->assertEquals($handled_error->error_string, "Undefined index: key that does not exist");
    }
}
