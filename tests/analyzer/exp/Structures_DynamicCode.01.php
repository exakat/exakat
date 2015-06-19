<?php

$expected     = array('$$a',
                      '$x[\'a\' . \'b\']',
                      '$object->$methodcall( )',
                      '$classname::methodcall( )',
                      '$classname::$methodcall( )',
                      'classname::$methodcall( )',
                      '$functioncall(2, 3, 3)',
                      '$classname( )',
                      'range($argument . 2, $arguments2 + 3)'
                      );

$expected_not = array();

?>