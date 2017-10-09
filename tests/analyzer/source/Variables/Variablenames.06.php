<?php

class x {
    static $staticProperty = 2;
}

function y () {
    static $staticVariable = 3;
    global $globalVariable;
    
    $variable = 3;
}
?>