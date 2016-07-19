<?php

$expected     = array( 'if (!defined(\'D\')) { /**/ } ', 
                       'if (!defined("H")) { /**/ } ', 
                       'if (!defined("G")) { /**/ } ', 
                       'if (!defined(\'E\')) { /**/ } ',
                       'if (!defined(\'A\')) { /**/ } ', 
                       'if (!defined(\'C\')) { /**/ } ', 
                       'if (!defined(\'I\')) { /**/ } ', 
                       'defined(\'B\') or die("b")', 
                       'defined(\'F\') or die(\'f\')',
                       'defined(\'J\') or die(\'i\')'
                       );

$expected_not = array();

?>