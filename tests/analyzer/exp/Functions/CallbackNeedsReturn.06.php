<?php

$expected     = array('array_map(function ($n) use ($b) { /**/ } , $b)',
                     );

$expected_not = array('array_map(function (&$n) { /**/ } , $a)',
                      'array_map(function ($n) { /**/ } , $a)',
                      'array_map(function ($n) use (&$b) { /**/ } , $b)',
                     );

?>