<?php

class x {
    function __call($name, $args) {
        // no return! 
    }

    public static function __callStatic($name, $args) {
        return true; 
    }

}
?>