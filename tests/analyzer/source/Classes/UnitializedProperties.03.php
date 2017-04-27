<?php

$a = new class implements Logger { 
    function foo() {
        A::foo();
    }
} ;

class A implements Logger { 
    function foo() {
        A::foo();
    }
}

?>