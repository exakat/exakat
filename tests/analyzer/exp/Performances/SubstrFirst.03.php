<?php

$expected     = array('$c = preg_replace(\'a\', \'b\', $d)',
                      '$salt = str_replace(array(\'+\', \'=\'), \'.\', base64_encode(sha1(uniqid(\'salt\', true), true)))',
                     );

$expected_not = array('$f = rtrim($g)',
                      '$r = trim($c)',
                     );

?>