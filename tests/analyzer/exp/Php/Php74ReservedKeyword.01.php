<?php

$expected     = array('\\fn\\a',
                      'fn\\a',
                      'a\\fn',
                      'a\\fn\\a',
                      'FN',
                      'function fn( ) { /**/ } ',
                      'class fn extends b { /**/ } ',
                      'fn',
                     );

$expected_not = array('private function fn( ) { /**/ }',
                      'b\\fnd\\a',
                     );

?>