<?php

$expected     = array('$_SERVER',
                      '$_ENV[\'a\']',
                      '$_FILES[\'b\'][\'3\']',
                      '$_GET',
                      '$_POST[\'s\']',
                      '$_REQUEST[\'a\'][\'b\']',
                     );

$expected_not = array('$_REQUEST',
                     );

?>