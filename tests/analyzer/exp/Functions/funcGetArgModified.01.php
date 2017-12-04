<?php

$expected     = array('function funcGetArgModified2($a, $b, $c) { /**/ } ',
                      'function funcGetArgModified($a) { /**/ } ',
                      'function funcGetArgModified1($a, $b) { /**/ } ',
                     );

$expected_not = array('function funcGetArgNotModified($a, $b, $c) { /**/ } ',
                      'function funcGetArgNotUsedAsVariable($a, $b, $c) { /**/ } ',
                      'function funcGetArgNotInArg($a, $b) { /**/ } ',
                      'noFuncGetArg($a, $b) { /**/ } ',
                     );

?>