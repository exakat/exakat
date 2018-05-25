<?php

$expected     = array('function xc(X $c) { /**/ } ',
                      'function xd($d) { /**/ } ',
                      'function xe(Y $e) { /**/ } ',
                     );

$expected_not = array('function xa(x $a) { /**/ } ',
                      'function xb(X $b) { /**/ } ',
                      'function xc(X $c) { /**/ } ',
                     );

?>