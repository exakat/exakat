<?php

$expected     = array('basename(str_replace(\'.php\', \'\', $path))', 
                      'str_replace(\'.php\', \'\', basename($path))',
                      'basename(str_ireplace(\'.php\', \'\', $path))', 
                      'str_ireplace(\'.php\', \'\', basename($path))',
                     );

$expected_not = array('BASENAME(str_ireplace(\'.php\', \'\', $path))', 
                     );

?>