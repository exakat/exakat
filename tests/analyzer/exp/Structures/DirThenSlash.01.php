<?php

$expected     = array('\'a\' . __DIR__ . "as$c"',
                      '__DIR__ . "as$b"',
                      '\'a\' . __DIR__ . \'asc\'',
                      '__DIR__ . \'asb\'',
                     );

$expected_not = array('\'a\' . __DIR__ . "/s$c"',
                      '__DIR__ . "/s$b"',
                      '\'a\' . __DIR__ . \'/sc\'',
                      '__DIR__ . \'/sb\'',
                     );

?>