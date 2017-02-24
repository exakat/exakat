<?php 

function a1(X $readOnlyA1 = null, X $writenOnlyA1 = null, X $readAndWrittenA1 = null, X $unusedA1 = null) { 
    $writenOnlyA1 = $readOnlyA1 + 1;
    $readAndWrittenA1 = $readAndWrittenA1 * $readOnlyA1;
    $localA1 = 1;
};

function a12(X $readOnlyA1 = null, X $writenOnlyA1 = null, X $readAndWrittenA1 = null) { 
    $writenOnlyA1 = $readOnlyA1 + 1;
    $readAndWrittenA1 = $readAndWrittenA1 * $readOnlyA1;
    $localA1 = 1;
};

function a2(X &$readOnlyA2 = null, X &$writenOnlyA2 = null, X &$readAndWrittenA2 = null, X &$unusedA2 = null) { 
    $writenOnlyA2 = $readOnlyA2 + 1;
    $readAndWrittenA2 = $readAndWrittenA2 * $readOnlyA2;
    $localA2 = 1;
};

function a3(X &$readOnlyA2 = null, X &$writenOnlyA2 = null, X &$readAndWrittenA2 = null) { 
    $writenOnlyA2 = $readOnlyA2 + 1;
    $readAndWrittenA2 = $readAndWrittenA2 * $readOnlyA2;
    $localA2 = 1;
};

$a = function () use (&$readOnlyClosureR, &$writenOnlyClosureR, &$readAndWrittenClosureR) { 
    $writenOnlyClosureR = $readOnlyClosureR + 1;
    $readAndWrittenClosureR = $readAndWrittenClosureR * $readOnlyClosureR;
    $localClosureR = 1;
};

$a = function () use (&$readOnlyClosureR, &$readAndWrittenClosureR, &$unusedClosureR) { 
    $writenOnlyClosureR = $readOnlyClosureR + 1;
    $readAndWrittenClosureR = $readAndWrittenClosureR * $readOnlyClosureR;
    $localClosureR = 1;
};

$a = function () use ($readOnlyClosure, $readAndWrittenClosure, $unusedUseClosure) { 
    $writenOnlyClosure = $readOnlyClosure + 1;
    $readAndWrittenClosure = $readAndWrittenClosure * $readOnlyClosure;
    $localClosure = 1;
};

$a = function () use ($readOnlyClosure, $writenOnlyClosure, $readAndWrittenClosure) { 
    $writenOnlyClosure = $readOnlyClosure + 1;
    $readAndWrittenClosure = $readAndWrittenClosure * $readOnlyClosure;
    $localClosure = 1;
};

interface i {
    function interfaceMethod($interfaceArgument) ;
}

abstract class c {
    abstract function abstractClassMethod($abstractClassArgument) ;
             function         ClassMethod($ClassArgument) {}
}

trait t {
    abstract function abstractClassMethod($abstractTraitArgument) ;
             function         ClassMethod($traitArgument) {}
}

?>
