<?php

$expected     = array('file($file5)',
                      'implode(\'\', file($file1))',
                      'join(\'\', \\file($file2))',
                     );

$expected_not = array('file($file6)',
                      '\\file($file7)',
                      'join(\'sb\', \\file($file2))',
                     );

?>