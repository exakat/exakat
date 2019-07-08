<?php

class x {
    function __call($name, $arg) {
        // no return
    }
    
    function __callstatic($name, $arg) {
        throw new exception();
    }
}
?>