<?php

$expected     = array('function ( ) use (&$a) { /**/ } ',
                     );

$expected_not = array('function ( ) use ($b) { /**/ } ',
                      'function ($c) { /**/ } ',
                      'function (&$d) { /**/ } ',
                     );

?>