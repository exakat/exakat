<?php

$expected     = array('${strtolower($a4)}',
                      '${strtolower($a3)}',
                      '$b->{strtolower($a2)}( )',
                      '$b->{strtolower($a1)}',
                      '${A}',
                     );

$expected_not = array('$b->{$c}',
                      '$b->{$d}( )',
                      '${$e}',
                     );

?>