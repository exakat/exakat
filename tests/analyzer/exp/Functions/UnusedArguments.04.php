<?php

$expected     = array('function ( ) use (&$readOnlyClosureR, &$readAndWrittenClosureR, &$unusedClosureR) { /**/ } ',
                      'function ( ) use ($readOnlyClosure, $writenOnlyClosure, $readAndWrittenClosure) { /**/ } ',
                      'function ( ) use ($readOnlyClosure, $readAndWrittenClosure, $unusedUseClosure) { /**/ } ',
                      'function a2(X &$readOnlyA2 = null, X &$writenOnlyA2 = null, X &$readAndWrittenA2 = null, X &$unusedA2 = null) { /**/ } ',
                      'function ClassMethod($ClassArgument) { /**/ } ',
                      'function ClassMethod($traitArgument) { /**/ } ',
                      'function a1(X $readOnlyA1 = null, X $writenOnlyA1 = null, X $readAndWrittenA1 = null, X $unusedA1 = null) { /**/ } ',
                      'function a12(X $readOnlyA1 = null, X $writenOnlyA1 = null, X $readAndWrittenA1 = null) { /**/ } ',
                     );

$expected_not = array('function a3(X &$readOnlyA2 = null, X &$writenOnlyA2 = null, X &$readAndWrittenA2 = null) { /**/ } ',
                     );

?>