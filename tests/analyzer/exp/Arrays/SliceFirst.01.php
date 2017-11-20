<?php

$expected     = array('array_slice(array_map(\'foo\', $array), 2, 5)',
                     );

$expected_not = array('array_map(\'foo\', array_slice($array, 2, 5));',
                     );

?>