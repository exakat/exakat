<?php

function foo8() {
    $x = array(1,2,3,4,5,6,7,8);
}

function foo15() {
    $x = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15);
}

function foo2() {
    $x = array(1,2);
}

class x {
    function foox7() {
        // assign to non local is good
        $s = array(1,2,3,4,5,6,7);
    }

    function foox11() {
        // assign to non local is good
        $s = array(1,2,3,4,5,6,7,8,9,10,11);
    }

    function foox15() {
        // assign to non local is good
        $s = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15);
    }
}

?>