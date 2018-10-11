<?php

class foo {
    static public $buggy = 1;
    static public $OK1 = 1;
    static public $OK2 = 1;
    static public $OK3 = 1;
}

class bar extends foo {
    static public $OK2 = 1; // when overloaded, no problemo
}

bar::$buggy = &$f;
bar::$OK2 = &$f;
$f = 3;
bar::$OK1 = $f;
bar::$OK3 = 3;

print bar::$OK1.' <-> '.foo::$OK1.PHP_EOL;
print bar::$OK2.' <-> '.foo::$OK2.PHP_EOL;
print bar::$OK3.' <-> '.foo::$OK3.PHP_EOL;
print bar::$buggy.' <-> '.foo::$buggy.PHP_EOL;

?>