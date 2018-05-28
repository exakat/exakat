<?php

$expected     = array('function &fooC($i) : X { /**/ } ',
                      'function &fooT($i) : X { /**/ } ',
                      'function &fooI($i) : X ;',
                      'function &(X $i) : X { /**/ } ',
                     );

$expected_not = array('function  fooT($i) : X { /**/ } ',
                      'function &fooI($i) { /**/ } ',
                      'function  fooT($i) : X { /**/ } ',
                      'function &fooI($i) { /**/ } ',
                     );

?>