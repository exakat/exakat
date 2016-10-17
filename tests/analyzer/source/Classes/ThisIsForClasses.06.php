<?php

try {

} catch (ExceptionOutsideClass $this) {

}

class foo {
    function bar() {
        try {
        
        } catch (ExceptionInsideClass $this) {
        
        }
    }
}

trait t {
    function bar() {
        try {
        
        } catch (ExceptionInsideTrait $this) {
        
        }
    }
}

?>