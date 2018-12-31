<?php

$expected     = array('basename(str_ireplace(0, 1, $path))',
                      'basename(str_replace(0, 1, $path))',
                      'basename(substr(1, $basename))',
                     );

$expected_not = array('basename(mb_substr($path, 0, 1))',
                     );

?>