<?php

$expected     = array('class x implements i, ac',
                      'class a implements ac2, ac', 
                      'class z2 extends ac implements ac2', 
                      'class x implements ac, i');

$expected_not = array('class y extends ac');

?>