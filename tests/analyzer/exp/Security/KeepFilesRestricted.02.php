<?php

$expected     = array('chmod($f, -1)',
                      'chmod($f, (0777))',
                      'chmod($f, (rand(0, 1) ? $a : 0777))',
                      'chmod($f, (rand(0, 1) ? $a : foo( )))',
                      'chmod($f, (rand(0, 1) ? $a : hoo( )))',
                      'chmod($f, $a)',
                      'chmod($f, 0777)',
                     );

$expected_not = array('chmod($f, 0770)',
                     );

?>