<?php

$expected     = array('\'(null, "\' . $a->sqlite->escapeString($name) . \'",  \\\'$type\\\',   $count)\'',
                      '"(null, \"" . $a->sqlite->escapeString($name) . "\",  \'$type\',   $count)"',

                     );

$expected_not = array('"(null, \'" . $a->sqlite->escapeString($name) . "\', \'$type\', $count)"',
                     );

?>