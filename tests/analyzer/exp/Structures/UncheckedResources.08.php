<?php

$expected     = array('$a->b = fopen(__DIR__ . \'/server.log2\', \'a\')',
                     );

$expected_not = array('$a->b = fopen(__DIR__ . \'/server.log1\', \'a\')',
                      '$a->b = fopen(__DIR__ . \'/server.log3\', \'a\')',
                     );

?>