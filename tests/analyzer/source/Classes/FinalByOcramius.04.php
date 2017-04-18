<?php

class A implements i {
    function i1() {}
}

class B extends A {
    function i1() {}
}

class C extends B {
    function i1() {}
}

final class D extends B {
    function i1() {}
}

interface i {
    function i1() ;
}

?>