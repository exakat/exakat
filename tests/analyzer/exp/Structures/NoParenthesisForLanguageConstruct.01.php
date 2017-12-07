<?php

$expected     = array('print (4)',
                      'echo (2)',
                      'return (1)',
                      'throw (new Exception( ))',
                      'require_once (\'file.php\')',
                      'include (\'file.php\')',
                      'include_once (\'file.php\')',
                      'require (\'file.php\')',
                     );

$expected_not = array('print (\'4b\')',
                      'echo (\'2b\')',
                      'return (12)',
                      'throw (new Exception2( ))',
                      'require_once (\'fileb.php\')',
                      'include (\'fileb.php\')',
                      'include_once (\'fileb.php\')',
                      'require (\'fileb.php\')',
                     );

?>