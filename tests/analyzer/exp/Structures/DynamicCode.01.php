<?php

$expected     = array('$$a',
                      '$x[\'a\' . \'b\']',
                      '$object->$methodcall( )',
                      '$classname::methodcall( )',
                      '$classname::$methodcall( )',
                      'classname::$methodcall( )',
                      '$functioncall(2, 3, 3)',
                      '$classname( )'
                      );

$expected_not = array('range($argument . 2, $arguments2 + 3)');

?>