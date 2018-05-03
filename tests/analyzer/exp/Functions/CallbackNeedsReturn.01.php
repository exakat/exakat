<?php

$expected     = array('array_map(function ($n2) { /**/ } , $a)',
                      'array_map("cube2", $a)',
                     );

$expected_not = array('array_map(function ($n) { /**/ } , $a)',
                      'array_map("cube", $a)',
                     );

?>