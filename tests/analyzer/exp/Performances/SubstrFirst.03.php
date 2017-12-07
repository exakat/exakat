<?php

$expected     = array('preg_replace(\'a\', \'b\', $d)',
                      'str_replace(array(\'+\', \'=\'), \'.\', base64_encode(sha1(uniqid(\'salt\', true), true)))',
                     );

$expected_not = array('trim($g)',
                     );

?>