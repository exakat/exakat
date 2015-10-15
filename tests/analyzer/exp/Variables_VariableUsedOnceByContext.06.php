<?php

$expected     = array('$d',
                      '$c',
                      '$e',
                      '$d' // $d is in the closure and not in the closure
);

$expected_not = array('$b');

?>