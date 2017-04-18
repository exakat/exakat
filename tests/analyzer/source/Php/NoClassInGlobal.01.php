<?php

namespace {
    class x {}
    trait t {}
    interface i {}
    function f() {}
    
    $a = new class {};
    $a = function () {};
}

namespace T {
    class x1 {}
    trait t1 {}
    interface i1 {}
    function f1() {}
    
    $a = new class {};
    $a = function () {};
}

?>