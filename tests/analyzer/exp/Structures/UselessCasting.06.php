<?php

$expected     = array('(array) foo( )',
                      '(array) array_merge([ ], [ ])',
                     );

$expected_not = array('(array) goo( )',
                      '(bool) goo( )',
                      '(string) goo( )',
                      '(bool) foo( )',
                      '(string) foo( )',
                      '(bool) array_merge([ ], [ ])',
                      '(string) array_merge([ ], [ ])',
                     );

?>