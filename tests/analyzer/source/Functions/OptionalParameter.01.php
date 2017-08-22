<?php
    
class foo {
    function methodWithOptionalArgument(bar $x = null) {
        if ($x === null) {
            // default behavior
        } else {
            // normal behavior
        }
    }

    function methodWithCompulsoryArgument(bar $x) {
        // normal behavior
        // $x is always a bar. 
    }
}
?>
