<?php

$expected     = array('1 + $a2',
                      '1 + $a3', 
                      '1 + $a4', 
                      '1 + $a5', 
                      '1 + foo( )',
                      '1 + $o->p',
                     );

$expected_not = array('$a + 1',
                     );

?>