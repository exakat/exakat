<?php

$expected     = array('$fp = fopen(__DIR__ . \'/server.log2\', \'a\')',
                     );

$expected_not = array('$fp = fopen(__DIR__ . \'/server.log1\', \'a\')',
                      '$fp = fopen(__DIR__ . \'/server.log3\', \'a\')',
                     );

?>