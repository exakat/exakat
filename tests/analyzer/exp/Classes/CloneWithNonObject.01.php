<?php

$expected     = array('clone true', 
                      'clone $x[1]', 
                      'clone $x->d', 
                      'clone x', 
                      'clone array(\'x\')'
                     );

$expected_not = array('clone new Stdclass', 
                      'clone fooX()',
                      'clone $a->fooX()',
                     );

?>