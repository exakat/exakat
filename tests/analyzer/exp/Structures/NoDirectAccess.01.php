<?php

$expected     = array('if(!defined(\'D\')) { /**/ } ',
                      'if(!defined(\'E\')) { /**/ } ',
                      'if(!defined(\'A\'))  /**/  ',
                      'if(!defined(\'C\'))  /**/  ',
                      'if(!defined(\'I\'))  /**/  ',
                      'if(!defined("G")) { /**/ } ',
                      'if(!defined("H")) { /**/ } ',
                      'defined(\'B\') or die("b")',
                      'defined(\'F\') or die(\'f\')',
                      'defined(\'J\') or die(\'i\')',
                     );

$expected_not = array('defined(\'K\') or define(\'K\', 1)',
                     );

?>