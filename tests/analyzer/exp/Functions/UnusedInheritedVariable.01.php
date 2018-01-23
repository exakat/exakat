<?php

$expected     = array('function ($y) use ($U) { /**/ } ',
                      'function ($y) use ($U, $V) { /**/ } ',
                      'function ($y) use ($U, $V, $W) { /**/ } ',
                      'function ($y) use ($U, $V, $W, $a) { /**/ } ',
                     );

$expected_not = array('function ($y) use ($u) { return $u; };',
                      'function ($y, $z) use ($u) { return $u; };',
                     );

?>