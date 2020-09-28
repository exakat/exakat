<?php

$expected     = array('$a',
                      '$b',
                      '$g',
                      '$f',
                      '$e',
                      '$d',
                      '$c',
                      '$GLOBALS',
                      '$GLOBALS[\'G\']',
                      '$GLOBALS[\'J\']',
                      '$GLOBALS[\'H\']',
                      '$GLOBALS[\'H\'][\'I\']',
                      '$GLOBALS[\'J\'][\'I\']',
                     );

$expected_not = array('$z',
                     );

?>