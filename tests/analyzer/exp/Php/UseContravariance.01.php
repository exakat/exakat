<?php

$expected     = array('function m(X $z) { /**/ } ',
                     );

$expected_not = array('function m(X $zz) { /**/ } ',
                      'function m(X $zz) { /**/ } ',
                      'function m(Y $z3) { /**/ } ',
                     );

?>