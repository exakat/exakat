<?php

// Not a method
function isHashFunction() {}

class x {
    // is => bool
    function isHash2() {}

    // has => bool
    function hasHash2() {}

    // has => bool
    function hasNotHash2() {}

    // has => bool
    function hasNotHash3() : bool {}

    // has => bool
    function hasNotHash4() : ?bool {}

    // has => bool
    function hasNotHash5() : ?int {}
}

?>