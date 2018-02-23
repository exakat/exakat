<?php

$expected     = array('preg_match(\'(VI)a\', $url)',
                     );

$expected_not = array('preg_match( \'\\(VG\\)\', $url)',
                      'preg_match( \'\\(VH\\)a\', $url)',
                     );

?>