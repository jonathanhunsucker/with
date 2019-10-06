<?php

namespace JonathanHunsucker\With\Tests;

use PHPUnit\Framework\TestCase;

use function JonathanHunsucker\With\with;

use JonathanHunsucker\With\{Context, Open, Timer};

class BasicUseCase extends TestCase
{
    public function testTimer(): void
    {
        $timer = new Timer();

        with($timer)->do(function () {
            sleep(1);
        });

        $this->assertEqualsWithDelta(1, $timer->elapsed(), 0.01);
    }

    public function testOpen(): void
    {
        touch("test.txt");
        $file = new Open("test.txt", "w");

        with($file)->do(function ($handle) {
            // this is awful to manipulate files in concurrently run tests, but it'll do for now
            fwrite($handle, "hello");
        });

        $content = file_get_contents("test.txt");
        unlink("test.txt");

        $this->assertEquals($content, "hello");
    }

    public function testResilienceToExceptions(): void
    {
        $notes_its_own_exit = new class implements Context {
            public $exit_did_run = false;

            public function enter()
            {
                return null;
            }

            public function exit()
            {
                $this->exit_did_run = true;
            }
        };

        try {
            with($notes_its_own_exit)->do(function ($_) {
                throw new \Exception("Thrown inside the body");
            });
        } catch (\Exception $e) {
            // swallow it
        }

        $this->assertTrue($notes_its_own_exit->exit_did_run);
    }
}
