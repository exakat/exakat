<?php

class x {
    function usedMethod() {}
    function definedTwiceMethod() {}
    function unusedMethod() {}
    function methodNamedAsAFunction() {}
    function usedMethodNoCase() {}
    function usedMethodStatically() {}
}

class y {
    function definedTwiceMethod() {}
}

$y->usedMethod();
$yy->definedTwiceMethod();
$xxx->usedmethodnocase();
x::usedMethodStatically();
methodNamedAsAFunction();

?>