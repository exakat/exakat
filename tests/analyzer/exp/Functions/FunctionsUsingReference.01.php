<?php

$expected     = array('function xRef001($a, $b, &$c) { /**/ } ',
                      'function xRef010($a, &$b, $c) { /**/ } ',
                      'function xRef011($a, &$b, &$c) { /**/ } ',
                      'function xRef110(&$a, &$b, $c) { /**/ } ',
                      'function xRef111(&$a, &$b, &$c) { /**/ } ',
                      'function xRef100(&$a, $b, $c) { /**/ } ',
                      'function xRefVariations2(X &$a = null) { /**/ } ',
                      'function xRef101(&$a, $b, &$c) { /**/ } ',
                      'function xRefVariations(&$a = 1) { /**/ } ',
                     );

$expected_not = array('function x($a, $b, $c) { /**/ } ',
                     );

?>