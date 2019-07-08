<?php

$expected     = array('preg_replace(\'/abc\063/\', $r, $b)', 
                      'preg_replace("/abc\063/", $r, $b)', 
                      'preg_replace_callback(\'/abc/\', $r, $b)', 
                      'preg_match(A, $r)',
                     );

$expected_not = array('preg_replace(\'/abc\063/\', $r, $b)', 
                     );

?>