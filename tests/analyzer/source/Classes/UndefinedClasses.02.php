<?php

namespace {
    UndefinedClass1::constante;
    \UndefinedClass2::$property;
    A\UndefinedClass3::method();

    $y1 = DefinedClass1::constante;
    $y2 = \DefinedClass2::$property;
    $y3 = A\DefinedClass3::method();
}

namespace A {
    class DefinedClass3 { static function method() {}}
}

namespace {
    class DefinedClass1 { const constante = 1;}
    class DefinedClass2 { static public $c = null;}
}

?>