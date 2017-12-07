<?php

$expected     = array('header("set-Cookie: {$name}={$value}; EXPIRES{$date};")',
                      'header("Set-Cookie: {$name}={$value}; EXPIRES{$date};")',
                      'header(\'Set-Cookie: \' . $name . \'=\' . $value . \'; EXPIRES\' . $date . \';\')',
                     );

$expected_not = array('\'sat-cookie: {$name}={$value}; EXPIRES{$date};\'',
                     );

?>