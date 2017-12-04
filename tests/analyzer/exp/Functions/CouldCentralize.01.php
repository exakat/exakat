<?php

$expected     = array('str_replace(\'a\', \'b\', $c)',
                      'str_replace(\'a\', \'d\', $c)',
                      'str_replace(\'a\', \'e\', $c)',
                      'str_replace(\'a\', \'f\', $c)',
                      'str_replace(\'a\', \'g\', $c)',
                      'implode(\'i\', $a)',
                      'implode(\'i\', $a)',
                      'implode(\'i\', $a)',
                      'implode(\'i\', $a)',
                      'implode(\'i\', $a)',
                     );

$expected_not = array('str_replace(\'i\', \'g\', $c)',
                     );

?>