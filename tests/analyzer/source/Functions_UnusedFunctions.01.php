<?php

function unusedFunction() {}
function usedFunction() {}

usedFunction();

class x {
    function unusedMethod() {}
    function usedMethod() {
        $this->usedMethod();
    }
    
}

?>