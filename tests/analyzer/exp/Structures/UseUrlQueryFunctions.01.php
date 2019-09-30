<?php

$expected     = array('implode(\'&\', $data)',
                     );

$expected_not = array('http_build_query($data, \'\', \'&\')',
                     );

?>