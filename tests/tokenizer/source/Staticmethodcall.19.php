<?php

class x {
    static function __callStatic($a, $b) {
        print $a;
        print __METHOD__;
    }
}


x::class();