<?php

namespace JonathanHunsucker\With;

class BoundContexts
{
    private $contexts;

    public function __construct(array $contexts)
    {
        $this->contexts = $contexts;
    }

    public function do($function)
    {
        $arguments = [];
        $entered = [];

        try {
            foreach ($this->contexts as $context) {
                $arguments[] = $context->enter();
                $entered[] = $context;
            }
        } catch (\Exception $e) {
            $this->exitAll($entered);
            throw new \RuntimeException("Failed to enter context", 0, $e);
        }

        try {
            call_user_func_array($function, $arguments);
        } finally {
            $this->exitAll($entered);
        }
    }

    private function exitAll($contexts)
    {
        foreach ($contexts as $context) {
            $context->exit();
        }
    }
}
