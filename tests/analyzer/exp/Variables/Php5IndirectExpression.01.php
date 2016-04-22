<?php

$expected     = array('$$foo[\'bar\'][\'baz\']',
                      '$foo->$bar[\'baz\']',
                      '$foo->$bar[\'baz\']', // Twice, because it is the base for the functioncall below
                      '$foo->$bar[\'baz\']( )',
                      'Foo::$bar[\'baz\']( )'
                      );

$expected_not = array('$foo2->$bar2[\'baz2\']');

?>