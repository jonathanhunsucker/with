<?php

namespace JonathanHunsucker\With;

function with(Context ...$contexts): BoundContexts
{
    return new BoundContexts($contexts);
}
