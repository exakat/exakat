<?php

trait t {
    function __clone() {
        $a = [];
        
        $c = [1];
        $d = [[], [1], [2]];
    }
}

?>