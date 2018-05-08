<?php

$expected     = array('$day < 1 && $day > 31',
                      '$day > 1 || $day < 31',
                     );

$expected_not = array('$day > 1 && $day < 31',
                      '$day < 1 || $day > 31',
                     );

?>