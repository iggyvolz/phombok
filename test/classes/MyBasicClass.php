<?php

namespace iggyvolz\phombok\test;

use iggyvolz\phombok\Attributes\Getter;

class MyBasicClass
{
    #[Getter]
    private int $foo = 1;

    public function hello(): string
    {
        return "Hello world!";
    }
}