<?php

$expected     = array('$$foo[\'bar\'][\'baz\']',
                      '$foo->$bar[\'baz\']',
                      'Foo::$bar[\'baz\']( )',
                      '$foo->$bar[\'baz2\']( )',
                      );

$expected_not = array('a::$b[$c]',
                      'Foo::$bar[\'baz\']',
                      'Foo::bar[\'baz\']');

?>