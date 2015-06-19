<?php

$expected     = array('$_SERVER = array( )', 
                      '$_ENV[\'a\'] += 2', 
                      '$_FILES[\'b\'][\'3\'] .= 4',
                      'unset($_GET)',
                      'unset($_POST[\'s\'])',
                      'unset($_REQUEST[\'a\'][\'b\'])',
);

$expected_not = array();

?>