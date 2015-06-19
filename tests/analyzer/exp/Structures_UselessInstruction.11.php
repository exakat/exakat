<?php

$expected     = array('( $a = ( new $x)) instanceof X',
                      '( $a = new $x) instanceof X',
                      '( new $x) instanceof X',
                      'new $x instanceof X',);

$expected_not = array();

?>