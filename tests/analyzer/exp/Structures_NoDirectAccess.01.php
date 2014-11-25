<?php

$expected     = array( 'if ( !defined(\'A\'))  /**/ ', 
                       'defined(\'B\') or die("b")',
                       'if ( !defined(\'C\'))  /**/ ', 
                       'if ( !defined(\'D\')) { /**/ } ', 
                       'if ( !defined(\'E\')) { /**/ } ', 
                       'defined(\'F\') or die(\'f\')',
                       'if ( !defined("G")) { /**/ } ', 
                       'if ( !defined("H")) { /**/ } ', 
                       'if ( !defined(\'I\'))  /**/ ',
                       'defined(\'J\') or die(\'i\')',
                       );

$expected_not = array();

?>