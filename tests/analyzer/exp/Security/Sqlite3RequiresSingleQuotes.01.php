<?php

$expected     = array('$x = $sqlite->escapeString($x)',
                      '\'select * from table where col = "\' . $sqlite->escapeString($x) . \'"\'',
                     );

$expected_not = array('"select * from table where col = \'" . $sqlite->escapeString($x) . "\'"',
                      '\'select * from table where col = "\' . escapeString($x) . \'"\'',
                     );

?>