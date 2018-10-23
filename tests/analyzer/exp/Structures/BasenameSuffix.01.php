<?php

$expected     = array('substr(basename($path), 0, 1)', 
                      'str_replace(a, b, basename($path))', 
                      'iconv_substr(basename($path), 0, 1)',
                     );

$expected_not = array('substr(1, basename($path), 1)',
                      'substr(1, $basename)',
                      'mb_substr(basename($path), 0, 1)',
                     );

?>