<?php

function unconditionalFunction() {}

define('normalConstant', 1);

if (!defined('x')) { 
    define("conditionedByX", 1);
}

function envelope() {
    if (!defined('Y')) { 
        define('conditionedByY', 2);
    }
}

?>
