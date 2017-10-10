<?php

class A {
    function keepTypehint(A $a) {}
    function dropTypehint(A $a) {}
    function addTypehint($a) {}
    function notOverwritten(A $a) {}
    function notOverwritten2() {}
}

class AA extends A { }

class AAA extends AA { }

class AAAA extends AAA { 
    function keepTypehint(A $a) {}
    function dropTypehint($a) {}
    function addTypehint(A $a) {}
    function notInParent(A $a) {}
    function notInParent2() {}
}

?>