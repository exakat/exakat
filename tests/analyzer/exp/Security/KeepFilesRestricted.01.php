<?php

$expected     = array('chmod($f, $bi)',
                      'chmod($f, $ci)',
                      'chmod($f, (rand(0, 1) ? $a : $ai))',
                      'chmod($f, $ai)',
                     );

$expected_not = array('chmod($f, $di)',
                      '',
                     );

?>