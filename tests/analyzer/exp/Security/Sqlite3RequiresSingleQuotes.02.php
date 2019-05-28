<?php

$expected     = array('$x = $sqlite->escapeString($x)',
                      '\'select * from table where col = "\' . $sqlite->escapeString($x) . CLOSE_DOUBLE_QUOTE',
                     );

$expected_not = array('"select * from table where col = \'" . $sqlite->escapeString($x) . CLOSE_QUOTE',
                      '\'select * from table where col = "\' . escapeString($x) . CLOSE_QUOTE',
                     );

?>