<?php

$expected     = array('(array) (new x)->bar( )', 
                      '(string) foo( )', 
                      '(string) (new x)->bar( )',
                     );

$expected_not = array('(bool) foo( )',
                      '(int) foo( )',
                     );

?>