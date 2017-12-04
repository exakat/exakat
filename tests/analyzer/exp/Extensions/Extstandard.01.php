<?php

$expected     = array('unserialize(strval($y))',
                      'strval($y)',
                     );

$expected_not = array('file_get_contents(\'/path/to/file.txt\')',
                     );

?>