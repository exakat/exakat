<?php

function unconditionalFunction() {}

if (!defined('x')) { 
    function conditionedByX() {}
}

function envelope() {
    if (!defined('Y')) { 
        function conditionedByY() {}
    }
}

?>
