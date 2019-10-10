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

    public function testResilienceToExceptions(): void
    {
        $notes_its_own_exit = $this->instrumentedContext();

        try {
            with($notes_its_own_exit)->do(function ($_) {
                throw new \Exception("Thrown inside the body");
            });
        } catch (\Exception $e) {
            // swallow it
        }

        $this->assertTrue($notes_its_own_exit->exit_did_run);
    }

    public function testPartialFailureResultsInRuntimeException(): void
    {
        $successful = $this->instrumentedContext();
        $fails_during_enter = $this->failsOnEnterContext();

        $this->expectException(\RuntimeException::class);

        with($successful, $fails_during_enter)->do(function () {
            // never run
        });
    }

    public function testPartialFailureExitsContextsEnteredSoFar(): void
    {
        $successful = $this->instrumentedContext();
        $fails_during_enter = $this->failsOnEnterContext();

        try {
            with($successful, $fails_during_enter)->do(function () {
                // never run
            });
        } catch (\Exception $e) {
            // swallow exception
        }

        $this->assertTrue($successful->exit_did_run);
    }

    private function instrumentedContext()
    {
        return new class implements Context {
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
    }

    private function failsOnEnterContext()
    {
        return new class implements Context {
            public function enter()
            {
                throw new \Exception();
            }

            public function exit()
            {
                return null;
            }
        };
    }
}
