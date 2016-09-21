<?php

namespace {
    function foo() { echo __NAMESPACE__."\n";}
    function foo3() { echo __NAMESPACE__."\n";}
}

namespace A\B {
    function foo() { echo __NAMESPACE__."\n";}
    function foo2() { echo __NAMESPACE__."\n";}
}

namespace A\B\C {
    foo();
    foo2();
    foo3();
}

?>
