<?php

$expected     = array('function foo( ) { /**/ } ', 
                      'function foo2( ) { /**/ } ');

$expected_not = array('function foo( ) { /**/ } ', 
                      'function foo3( ) { /**/ } '); // should be found just once

?>