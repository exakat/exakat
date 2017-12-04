<?php

$expected     = array('\'a\' . dirname($someFilePath, 2) . "as$c"',
                      'dirname($someFilePath) . "as$b"',
                      '\'a\' . dirname($someFilePath, 2) . \'asc\'',
                      'dirname($someFilePath) . \'asb\'',
                     );

$expected_not = array('\'a\' . dirname($someFilePath, 2) . "/s$c"',
                      'dirname($someFilePath) . "/s$b"',
                      '\'a\' . dirname($someFilePath, 2) . \'/sc\'',
                      'dirname($someFilePath) . \'/sb\'',
                     );

?>