<?php

class A
{

    private const B = 1024**2;

    public function c()
    {
        3 <= self::B;
    }
}
