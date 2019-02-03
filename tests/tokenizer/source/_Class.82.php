<?php

class a extends b
{
    public function c()
    {
        $d = new class() implements e {
            public static function f()
            {
                ++$g;
            }
        };
    }
}
