<?php

$expected     = array('str_replace(\'
\', \'b\', $c)',
                      'str_replace(\'
\', \'b\', $c)',
                      'str_replace(\'
\', \'b\', $c)',
                      'str_replace(\'
\', \'b\', $c)',
                      'str_replace(\'
\', \'b\', $c)',
                     );

$expected_not = array('\\stri_replace("\\n", \'b\', $c)',
                     );

?>