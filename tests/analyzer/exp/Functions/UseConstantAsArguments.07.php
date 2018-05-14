<?php

$expected     = array('error_reporting(1)',
                      'htmlspecialchars($str, ENT_COMPAT | ENT_HTML423, \'UTF-8\')',
                     );

$expected_not = array('error_reporting(-1)',
                      'htmlspecialchars($str, ENT_COMPAT | ENT_HTML401, \'UTF-8\')',
                      'error_reporting(0)',
                     );

?>