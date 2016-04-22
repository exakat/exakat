<?php

$expected     = array('$$foo[\'bar\'][\'baz\']',
                      'Foo::$bar[\'baz\']',
                      '$foo->$bar[\'baz\']',
                      '$foo->$bar[\'baz\']',);

$expected_not = array('$$foo[\'bar\']', // Partial result
                      '$foo2->$bar2[\'baz2\']');

?>