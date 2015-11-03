<?php

namespace {
$x1 = new UndefinedClass1();
$x2 = new \UndefinedClass2();
$x3 = new A\UndefinedClass3();

$y1 = new DefinedClass1();
$y2 = new \DefinedClass2();
$y3 = new A\DefinedClass3();

}

namespace A {
    class DefinedClass3 {}
}

namespace {
    class DefinedClass1 {}
    class DefinedClass2 {}
}


?>