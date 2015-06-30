<?php

$expected     = array('join(\'.\', [ 1, 2, 3, 4, 5 ])',
                      '\\implode(\'.\', [ 1, 2, 3, 4, 5, 6 ])');

$expected_not = array('join(\'.\', [1,2,3,4])',
                      '$object->join(\'.\', [1,2,3,4])');

?>