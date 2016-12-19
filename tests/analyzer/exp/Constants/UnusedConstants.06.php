<?php

$expected     = array( '\'\\A\\B\\C\\FOO4\'', 
 '\'\\A\\B\\C\\D\\FOO\'', 
 '\'\\A\\B\\C\\D\\FOO2\'', 
 '\'\\A\\B\\C\\D\\FOO5\'', 
 '\'\\FOO\'', 
 '\'\\A\\B\\FOO\'', 
 '\'\\A\\B\\FOO2\'', 
 '\'\\A\\B\\FOO5\'');

$expected_not = array('FOO3',
                      'FOO', // the third one
                      'FOO4');

?>