<?php

$expected     = array('($type == \'Type\') ? 1 : array( )',
                     );

$expected_not = array('($type == \'Type\') ? new $type( ) : null',
                      '($type == \'Addition\') ? $a + $b : $a * $b',
                      '$result = empty($condition) ? $a : \'default value\'',
                      '$result = empty($condition2) ? $a : getDefaultValue()',
                      '$object = ($type == \'Type\') ? \'a\' : <<<H
HEREDOC
H;',
                      '$object = ($type == \'Type\') ? \'a\' : \'a\' . C',
                     );

?>