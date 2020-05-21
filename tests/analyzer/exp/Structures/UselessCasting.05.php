<?php

$expected     = array('(array) (new x)->bar( )',
                      '(string) foo( )',
                     );

$expected_not = array('(bool) foo( )',
                      '(int) foo( )',
                      '(string) (new x)->bar( )',
                     );

?>