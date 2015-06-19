<?php

function globalLevel() {
    function deepDefinedFunction() {
        function deepDefinedLevel2() {
        }
    }
    
    interface deepDefinedInterface {}
    
    class deepDefinedClass {}

    interface deepDefinedTrait {}
}

trait t {
function traitLevel() {
    function deepDefinedFunction() {
        function deepDefinedLevel2() {
        }
    }
}

}

class c {
function classLevel() {
    function deepDefinedFunction() {
        function deepDefinedLevel2() {
        }
    }
}

}

?>