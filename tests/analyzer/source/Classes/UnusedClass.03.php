<?php

// used in callback
class X {
    function y() {}
}

// never used
class X2 {
    function y() {}
}

// used but not in callback
class X3 {
    function y() {}
}

// normal usage
array_filter(array(1,2,3), array('X', 'Y'));

// in a array structure, but not for callback
someFunction(array(1,2,3), array('X3', 'Y'));

?>