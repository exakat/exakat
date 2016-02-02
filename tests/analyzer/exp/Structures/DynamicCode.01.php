<?php

$expected     = array('$$a',
                      '$object->$methodcall( )',
                      '$classname::methodcall( )',
                      '$classname::$methodcall( )',
                      'classname::$methodcall( )',
                      '$functioncall(2, 3, 3)',
                      '$classname( )'
                      );

$expected_not = array('range($argument . 2, $arguments2 + 3)',
                      '$x[\'a\' . \'b\']',
);

?>