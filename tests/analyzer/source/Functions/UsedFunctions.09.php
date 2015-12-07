<?php

function unusedFunction() {}
function usedFunction() {}

usedFunction();

trait t {
    function unusedTraitMethod() {}
    function usedTraitMethod() {
        $this->usedTraitMethod();
    }
    
}

interface i {
    function unusedInterfaceMethod();
    function usedInterfaceMethod();
}

function ($closure) {}

?>