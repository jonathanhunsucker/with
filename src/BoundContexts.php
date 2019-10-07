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
        $arguments = array_map(function (Context $context) {
            return $context->enter();
        }, $this->contexts);

        try {
            call_user_func_array($function, $arguments);
        } finally {
            foreach (array_reverse($this->contexts) as $context) {
                $context->exit();
            }
        }
    }
}
