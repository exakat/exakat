<?php

$expected     = array('function xa3(X $a) { /**/ } ',
                      'function xa5(A $a) { /**/ } ',
                      'function xa6(X $a) { /**/ } ',
                      'function xa7(X|A|Y $a) { /**/ } ',
                     );

$expected_not = array('function xa2(X|A $a) { /**/ } ',
                      'function xa4(X|A $a) { /**/ } ',
                     );

?>