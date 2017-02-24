<?php 

/// Functions
function a1($readOnlyA1, $writenOnlyA1, $readAndWrittenA1, $unusedA1) { 
    $writenOnlyA1 = $readOnlyA1 + 1;
    $readAndWrittenA1 = $readAndWrittenA1 * $readOnlyA1;
    $localA1 = 1;
};

function a12($readOnlyA1, $writenOnlyA1, $readAndWrittenA1) { 
    $writenOnlyA1 = $readOnlyA1 + 1;
    $readAndWrittenA1 = $readAndWrittenA1 * $readOnlyA1;
    $localA1 = 1;
};

function a2(&$readOnlyA2, &$writenOnlyA2, &$readAndWrittenA2, &$unusedA2) { 
    $writenOnlyA2 = $readOnlyA2 + 1;
    $readAndWrittenA2 = $readAndWrittenA2 * $readOnlyA2;
    $localA2 = 1;
};

/// Closures (use)
$a = function () use (&$readOnlyClosureR, &$readAndWrittenClosureR, &$unusedClosureR) { 
    $writenOnlyClosureR = $readOnlyClosureR + 1;
    $readAndWrittenClosureR = $readAndWrittenClosureR * $readOnlyClosureR;
    $localClosureR = 1;
};

$a = function () use (&$readOnlyClosureR, &$writenOnlyClosureR, &$readAndWrittenClosureR) { 
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


// Interfaces, classes, traits, simples.
interface i {
    function interfaceMethod($interfaceArgument) ;
}

abstract class c {
    abstract function abstractClassMethod($abstractClassArgument) ;
             function         ClassMethod($ClassArgument) {}
}

trait t {
    abstract function abstractTraitMethod($abstractTraitArgument) ;
             function         TraitMethod($traitArgument) {}
}

?>
