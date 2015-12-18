<?php 

function a1($readOnly, $writenOnly, $readAndWritten, $unused) { 
    $writenOnly = $readOnly + 1;
    $readAndWritten = $readAndWritten * $readOnly;
};

function a2(&$readOnly, &$writenOnly, &$readAndWritten, &$unused) { 
    $writenOnly = $readOnly + 1;
    $readAndWritten = $readAndWritten * $readOnly;
};

$a = function () use (&$readOnly, &$writenOnly, &$readAndWritten, &$unused) { 
    $writenOnly = $readOnly + 1;
    $readAndWritten = $readAndWritten * $readOnly;
};

$a = function () use ($readOnly, $writenOnly, $readAndWritten, $unused) { 
    $writenOnly = $readOnly + 1;
    $readAndWritten = $readAndWritten * $readOnly;
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
