<?php

$expected     = array('new $k(1)',
                      'new $k[\'j\'](1)',
                      'new $j',
                      'new $j[\'k\']',
                      '$l::m',
                      '$n[0]::p',
                      '$e::$f',
                      '$g[\'i\']::$h',
                      '$a::b( )',
                      '$c[\'d\']::d( )',
                     );

$expected_not = array('new w( )',
                     );

?>