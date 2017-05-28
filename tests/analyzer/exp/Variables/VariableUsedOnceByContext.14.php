<?php

$expected     = array('$b',
                      '$d',
                      '$c',
                      '$e',
                      '$d', // $d is in the closure and not in the closure
                      '$df',
                      '$g'
);

$expected_not = array('$ff',
                      '$this');

?>