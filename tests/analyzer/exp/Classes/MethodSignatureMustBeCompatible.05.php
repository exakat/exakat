<?php

$expected     = array('function xb($b) { /**/ } ',
                      'function xc(...$c) { /**/ } ',
                      'function xb2($b) { /**/ } ',
                      'function xc2(...$c) { /**/ } ',
                     );

$expected_not = array('function xa($a) { /**/ } ',
                      'function xd(...$d) { /**/ } ',
                     );

?>