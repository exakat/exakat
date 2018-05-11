<?php

$expected     = array('echo array_map(function ($x) { /**/ } , explode(\',\', ucfirst(substr($string, 0, 10))))',
                     );

$expected_not = array('echo array_map(function ($x) {}, explode(',
                      ', $string))',
                      'echo array_map(function ($x) {}, explode(',
                      ', ucfirst($string)));',
                      'echo array_map(function ($x) { return strtoupper($x); }, explode(',
                      ', ucfirst($string)))',
                      'echo array_map(function ($x) { return strtoupper(substr($x, 0, 1)); }, explode(',
                      ', ucfirst($string)))',
                     );

?>