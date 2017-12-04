<?php

$expected     = array('function ( ) use ($readOnlyClosure, $readAndWrittenClosure, $unusedUseClosure) { /**/ } ',
                      'function ( ) use ($readOnlyClosure, $writenOnlyClosure, $readAndWrittenClosure) { /**/ } ',
                      'function ( ) use (&$readOnlyClosureR, &$readAndWrittenClosureR, &$unusedClosureR) { /**/ } ',
                      'function a2(&$readOnlyA2, &$writenOnlyA2, &$readAndWrittenA2, &$unusedA2) { /**/ } ',
                      'function a1($readOnlyA1, $writenOnlyA1, $readAndWrittenA1, $unusedA1) { /**/ } ',
                      'function a12($readOnlyA1, $writenOnlyA1, $readAndWrittenA1) { /**/ } ',
                      'function TraitMethod($traitArgument) { /**/ } ',
                      'function ClassMethod($ClassArgument) { /**/ } ',
                     );

$expected_not = array('&$readOnly',
                      '&$writenOnly',
                      '&$readAndWritten',
                     );

?>