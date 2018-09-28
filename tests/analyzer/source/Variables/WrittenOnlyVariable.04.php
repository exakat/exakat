<?php

trait t {
    function x($a) {
        $a = "e";
        $d = new $a('c');
    }
}
?>