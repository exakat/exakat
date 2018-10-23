<?php

$expected     = array('iconv_substr(basename($path), 0, 1)',
                     );

$expected_not = array('iconv_substr(basename($path, \'c\'), 0, 1)',
                     );

?>