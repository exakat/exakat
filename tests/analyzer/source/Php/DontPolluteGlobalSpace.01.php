<?php
namespace {
    const A = 1;
    define('AA', 1);
    define('B\AA', 1);

    function foo() {}
    class C {}
    interface i {}
    trait t {}
}

namespace B {
    define('AAb', 1);
    define('B\AAb', 1);

    const A = 1;
    function foo() {}
    class C {}
    interface i {}
    trait t {}
}

?>