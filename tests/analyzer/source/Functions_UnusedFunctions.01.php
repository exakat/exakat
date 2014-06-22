<?php

function definedFunction() {} 

definedFunction();

undefinedFunction();
definedMethodUsedAsFunction();

$x->definedMethod();
x::definedStaticMethod();

class x { 
    function definedMethod() {}
    static function definedStaticMethod() {}
    function definedMethodUsedAsFunction() {}
    
}


?>