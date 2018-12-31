<?php

$expected     = array('md5(file_get_contents(\'/to/path\'))',
                     );

$expected_not = array('sha1(\'/to/path\')',
                      'md5_file(\'/to/path\')',
                     );

?>