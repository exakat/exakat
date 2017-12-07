<?php

$expected     = array('gzopen($filename, "w9")',
                      'gzwrite($zp, $s)',
                      'gzclose($zp)',
                     );

$expected_not = array('tempnam(\'/tmp\', \'zlibtest\')',
                     );

?>