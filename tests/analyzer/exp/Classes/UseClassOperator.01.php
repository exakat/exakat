<?php

$expected     = array('\'x\'',
                      '\'\\x\'',
                      '\'\\X\'',
                     );

$expected_not = array('"x$a"',
                      '\\a',
                     );

?>