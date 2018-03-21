<?php

$expected     = array('echo ucfirst(strtolower($a)), ucfirst(strtolower($b))',
                      'echo strtolower($a), strtolower($b), strtoupper($c), strtoupper($d)',
                      'foo(ucfirst(strtolower(join(\', \', split(\';\', $a)))))',
                     );

$expected_not = array('echo strtolower($a)',
                      'echo strtolower($a), strtolower($b)',
                      'foo(ucfirst(strtolower(join(\', \', split(\';\', $a)))))',
                     );

?>