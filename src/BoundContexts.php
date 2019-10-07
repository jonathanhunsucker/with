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
            try {
                // first, enter each context, one-by-one
                foreach ($this->contexts as $context) {
                    // recording their results for later invocation
                    $arguments[] = $context->enter();

                    // as well as marking that they were enterd
                    $entered[] = $context;
                }
            } catch (\Exception $e) {
                // if a context throws an exception during enter()
                // translate it to a runtime exception, pointing at the original cause
                throw new \RuntimeException("Failed to enter context", 0, $e);
            }

            // with the contexts entered and results ready, invoke the action
            call_user_func_array($function, $arguments);
        } finally {
            // exit an entered contexts
            foreach ($entered as $context) {
                $context->exit();
            }
        }
    }
}
