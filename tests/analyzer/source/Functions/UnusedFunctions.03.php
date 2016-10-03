<?php

namespace A {
    function foo() {}
    function foo2() {}
    function foo5() {}
}

namespace B {
    use function A\foo;
    use function A\foo2 as foo3;
    
    function foo4() {}
    function foo6() {}

    foo();  // from A
    foo2(); // undefined
    foo3(); // from A (foo2)
    foo4(); // local
    bar(); // undefined, looks like a method
    
    $x = function ($x) {};
    
    class foo {
        function bar() {}
    }
}

?>