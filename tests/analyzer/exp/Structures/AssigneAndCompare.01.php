<?php

$expected     = array('$id = strpos($string, $needle) !== false',
                     );

$expected_not = array('($id = strpos($string, $needle)) !== false',
                      '$found = strpos($string, $needle) === false',
                      '$isFound = strpos($string, $needle) !== false',
                     );

?>