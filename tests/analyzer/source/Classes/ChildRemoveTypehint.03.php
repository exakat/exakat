<?php

class A {
    function keepTypehint(A $a) {}
    function dropTypehint(A $a) {}
    function addTypehint($a) {}
    function notOverwritten(A $a) {}
    function notOverwritten2() {}
}

class AA extends A { 
    function keepTypehint(A $a) {}
    function dropTypehint($a) {}
    function addTypehint(A $a) {}
}

class AAA extends AA {
    function keepTypehint(A $a) {}
    function dropTypehint($a) {}
    function addTypehint(A $a) {}
 }

class AAAA extends AAA { 
    function keepTypehint(A $a) {}
    function dropTypehint($a) {}
    function addTypehint(A $a) {}
    function notInParent(A $a) {}
    function notInParent2() {}
}

?>