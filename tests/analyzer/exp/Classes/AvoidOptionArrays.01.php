<?php

$expected     = array('function __construct(array $options) { /**/ } ',
                     );

$expected_not = array('function foobar(array $options) { /**/ } ',
                      'function __construct(A $a, B $b, C $c, D $d) { /**/ } ',
                     );

?>