<?php

namespace {
    function foo() { echo __NAMESPACE__."\n";}
    foo3();
}

namespace A\B {
    function foo() { echo __NAMESPACE__."\n";}
    function foo2() { echo __NAMESPACE__."\n";}
    function foo5() { echo __NAMESPACE__."\n";}
}

namespace A\B\C {
    foo();
    foo2();
    foo3();
    foo4();
    foo5();
    
    function foo4() {}
}

namespace A\B\C\D {
    function foo() { echo __NAMESPACE__."\n";}
    function foo2() { echo __NAMESPACE__."\n";}
    function foo5() { echo __NAMESPACE__."\n";}
}

?>
