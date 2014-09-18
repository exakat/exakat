<?php

$expected     = array('$x instanceof aliascasenotok',
                      'aliascasenotok::x',
                      'aliascasenotok::$x',
                      'aliascasenotok::x( )',
                      'aliascasenotok $a',
                      'catch (aliascasenotok $e) { /**/ } ',
);

$expected_not = array();

?>