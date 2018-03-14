<?php

$expected     = array('file($file5)',
                     );

$expected_not = array('file($file6)',
                      'implode(\'<br />\', file($file1))',
                      '\\file($file7)',
                      'join(\'sb\', \\file($file2))',
                     );

?>