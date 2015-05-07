<?php

$expected     = array( 'if (!defined("G")) { /**/ } ', 
                       'if (!defined("H")) { /**/ } ', 
                       'if (!defined(\'D\')) { /**/ } ', 
                       'if (!defined(\'A\'))  /**/ ', 
                       'if (!defined(\'C\'))  /**/ ', 
                       'if (!defined(\'I\'))  /**/ ', 
                       'if (!defined(\'E\')) { /**/ } ',
                       'defined(\'B\') or die("b")', 
                       'defined(\'F\') or die(\'f\')',
                       'defined(\'J\') or die(\'i\')'
                       );

$expected_not = array();

?>