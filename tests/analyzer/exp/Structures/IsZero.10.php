<?php

$expected     = array('($e + 2) - 2',
                     );

$expected_not = array('$deg1 + ($deg2 - $deg1) / 11',
                      '$y + .5 * $h - .5 * $d',
                      '$t + (1 - $t) * log(1 + $f * ($e - $t) / (2 - $t)) / log(1 + $d)',
                      '($e / 2) - 2',
                     );

?>