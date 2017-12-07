<?php

$expected     = array('\'a\\b\\c\\x\'',
                      '\'\\a\\b\\c\\x\'',
                     );

$expected_not = array('"x$a"',
                      '\\a',
                      '\\x',
                      '\\X',
                      'x',
                     );

?>