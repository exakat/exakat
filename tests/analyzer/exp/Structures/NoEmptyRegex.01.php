<?php

$expected     = array('preg_replace(\'abc\', $d, $d)',
                      'preg_match(\'\', $b, $c)',
                      'preg_replace(\'1\' . $c, $d, $d)',
                     );

$expected_not = array('\'/d/\'',
                      'preg_replace_callback(\'/d/\'.$c, $d, $d)',
                     );

?>