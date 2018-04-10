<?php

interface i {
    function foo() ;
}

class AEmpty {}

class AWithFoo {
    function foo() {}
}

class AWithFooAndArg {
    function foo($a) {}
}

class AWithOtherThanFoo {
    function foo2($a) {}
}

class AWithFooAndOther {
    function foo() {}
    function foo2($a) {}
}

class AWithFooBadAndOther {
    function foo($d) {}
    function foo2($a) {}
}

?>