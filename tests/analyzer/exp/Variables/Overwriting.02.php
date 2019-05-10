<?php

$expected     = array('$b = strtolower($b)',
                      '$c = strtolower($c->o)', 
                      '$d = strtolower($d[3])',
                      '$g = function ($h) use ($g) { /**/ } ',
                     );

$expected_not = array('$b = strtolower($B)',
                      '$c = strtolower($c1->o)', 
                      '$d = strtolower($d1[3])',
                      '$e = function ($e) use ($f) { /**/ } ',
                      '$i = function ($j) use ($k) { /**/ } ',
                     );

?>