<?php

class oldStyleConstructor {
    function oldStyleConstructor() { 
        $y = $y + 1; 
    }
}

class newStyleConstructor {
    function __construct() { 
        $y = $y + 1; 
    }
}

?>