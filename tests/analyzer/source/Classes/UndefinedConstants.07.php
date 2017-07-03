<?php

class foo {
    const A = 78;

    public function b($d, $c = 'e')
    {
        echo static::A;
        echo static::B;
    }
}
