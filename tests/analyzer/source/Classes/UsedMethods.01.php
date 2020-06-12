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

$y = new x;
$y->usedMethod();

$yy = new y();
$yy->definedTwiceMethod();

$yy2 = new x();
$yy2->definedTwiceMethod();

$xxx = new x();
$xxx->usedmethodnocase();

x::usedMethodStatically();

methodNamedAsAFunction();

?>