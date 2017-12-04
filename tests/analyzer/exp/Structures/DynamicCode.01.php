<?php

$expected     = array('$$a',
                      '$object->$property',
                      '$object->$methodcall( )',
                      '$classname::methodcall( )',
                      'classname::$methodcall( )',
                      '$classname2::$methodcall2( )',
                      '$functioncall(2, 3, 3)',
                      '$classname( )',
                     );

$expected_not = array('range($argument . 2, $arguments2 + 3)',
                      '$x[\'a\' . \'b\']',
                     );

?>