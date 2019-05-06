<?php

$expected     = array('echo 33',
                      'echo $a->b',
                     );

$expected_not = array('echo 34',
                      '$qs .= " AND ".$e." > ".$value',
                      'yes',
                     );

?>