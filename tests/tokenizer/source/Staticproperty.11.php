<?php

class Foo{
    static $bar = 'Nar';
}

class Nar {
    static $e = 'yes';
}
echo Foo::$bar::$e;
echo Foo::$bar::$e::$f::$g;