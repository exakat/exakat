<?php

$expected     = array('$a', 
                      '$a->b', 
                      '$a[1]', 
                      '{$$c}');

$expected_not = array('{foo()}',
                      '$a$b');

?>