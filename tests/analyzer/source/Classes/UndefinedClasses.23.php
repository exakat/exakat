<?php

class x {
    function foo() {
        $x instanceof parent;
    }
}

class y extends \Exception {
    function foo() {
        $y instanceof parent;
    }
}

class z extends x {
    function foo() {
        $z instanceof parent;
    }
}


?>