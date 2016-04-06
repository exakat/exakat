<?php

$expected     = array('$a || $a',
                      '$a || $c || $a',
                      '$a || $a || $c',
                      '$a || ( $c || $a )'
);

$expected_not = array('$c && $a && $b');

?>