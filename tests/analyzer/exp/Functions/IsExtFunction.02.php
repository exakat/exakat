<?php

$expected     = array('exit($f)',
                      'unset($b)',
                      'isset($a)',
                      'echo $c',
                      'strtolower($e)',
                      'die($g)',
                      'print ($d)',
                     );

$expected_not = array('myfunction( )',
                     );

?>