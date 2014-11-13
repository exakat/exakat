<?php

$expected     = array('class x2 implements i, ac',
                      'class a implements ac2, ac', 
                      'class z2 extends ac implements ac2', 
                      'class x implements ac, i');

$expected_not = array('class z extends ac implements i',
                      'class y extends ac');

?>