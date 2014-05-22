<?php

interface x {
    function interfaceMethod();
    function interfaceMethod2();    
}

abstract class x {
    function classMethod() {}
    abstract function classMethod2();    
}

trait x {
    function traitMethod() {}
    abstract function traitMethod2();    
}

?>