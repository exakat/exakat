<?php

$expected     = array('function ( ) use (&$readOnlyClosureR, &$writenOnlyClosureR, &$readAndWrittenClosureR, &$unusedClosureR) { /**/ } ',
                      'function ( ) use ($readOnlyClosure, $writenOnlyClosure, $readAndWrittenClosure) { /**/ } ',
                      'function ( ) use ($readOnlyClosure, $readAndWrittenClosure, $unusedUseClosure) { /**/ } ',
                      'function a2(X &$readOnlyA2, X &$writenOnlyA2, X &$readAndWrittenA2, X &$unusedA2) { /**/ } ',
                      'function ClassMethod($traitArgument) { /**/ } ',
                      'function ClassMethod($ClassArgument) { /**/ } ',
                      'function a12(X $readOnlyA12, X $writenOnlyA12, X $readAndWrittenA12) { /**/ } ',
                      'function a1(X $readOnlyA1, X $readAndWrittenA1, X $unusedA1) { /**/ } ',
                     );

$expected_not = array('function a0(X $readOnlyA1, X $writenOnlyA1) { /**/ } ',
                     );

?>