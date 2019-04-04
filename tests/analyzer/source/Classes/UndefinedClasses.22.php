<?php

class x {
    function foo(parent $x) {}
}

class y extends \Exception {
    function foo(parent $y) {}
}

class z extends x {
    function foo(parent $z) {}
}


?>