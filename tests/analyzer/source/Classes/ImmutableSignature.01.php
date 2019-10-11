<?php

class a {
    function foo($a) {}
    function foo2($a) {}
}

class ab1 extends a {
    function foo($ab1) {}
    function foo2($ab1, $c2) {}
}

class ab2 extends a {
    function foo($ab2) {}
}

class abc1 extends ab1 {
    function foo2($abc1) {}
}

class abc2 extends ab1 {
    function foo3($abc2) {}
    function foo2($abc2) {}
}

class abc3 extends ab1 {
    function foo2($abc3) {}
}

class abcd1 extends abc3 {
    function foo2($abcd1) {}
}

class abcd2 extends abc3 {
    function foo2($abcd2) {}
}

class abcd3 extends abc3 {
    function foo2($abcd3) {}
}

class abcd4 extends abc3 {
    function foo2($abcd4) {}
}

?>