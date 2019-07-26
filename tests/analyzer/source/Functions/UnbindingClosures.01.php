<?php

class x {
    private $a = 3;
    
    function foo() {
        return function () { echo $this->a; };
    }
}

$closure = (new x)->foo();

// $this was expected, and it is not anymore
$closure->bindTo(null);

$closure->bindTo(new x);

?>
