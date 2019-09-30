<?php

$expected     = array('function xd($d) : X { /**/ } ',
                      'function xc($c) : X { /**/ } ',
                     );

$expected_not = array('function xa($a) : x { /**/ } ',
                      'function xa2($a) : \\x { /**/ } ',
                      'function xa3($a) : x3 { /**/ } ',
                      'function xb($b)) : X { /**/ } ',
                      'function xc($c)) : X { /**/ } ',
                     );

?>