<?php

trait A {

    static function b()
    {
        parent::$d;
        return static::$c;
    }

}

PARENT::$d;
STATIC::$c;
