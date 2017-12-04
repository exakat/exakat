<?php

$expected     = array('unserialize(\'{}\')',
                     );

$expected_not = array('unserialize(\'{}\', [\'Foo\']',
                      'unserialize($b)',
                     );

?>