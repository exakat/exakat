<?php

$expected     = array('($type == \'Addition\') ? $a + $b : $a * $b', 
                      '($type == \'Type\') ? new $type( ) : null',);

$expected_not = array('$result = empty($condition) ? $a : \'default value\'',
                      '$result = empty($condition2) ? $a : getDefaultValue()',
);

?>