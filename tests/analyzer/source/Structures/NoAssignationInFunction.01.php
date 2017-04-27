<?php

function foo() {
    $x = array(1,2,3,4,5,6,7,8,9,10,11);
}

function foo2() {
    return array(1,2,3,4,5,6,7,8,9,10,11);
}

class x {
    function foox() {
        // assign to non local is good
        $s = array(1,2,3,4,5,6,7,8,9,10,11);
    }
    function foox2() {
        // assign to non local is good
        $this->s = array(1,2,3,4,5,6,7,8,9,10,11);
    }
}

?>