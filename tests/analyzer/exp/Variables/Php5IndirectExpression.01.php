<?php

$expected     = array('$$foo[\'bar\']',
                      '$$foo[\'bar\'][\'baz\']',
                      'Foo::$bar[\'baz\']',
                      '$foo->$bar[\'baz\']',
                      '$foo->$bar[\'baz\']',
                     );

$expected_not = array(
                     );

?>