<?php

$expected     = array('\'constraints\' => array(\'error\' => \'[0-9;+\',  )',
                     );

$expected_not = array('\'constraints\' => array(\'moduleName\' => \'[a-zA-Z][a-zA-Z0-9_-]*\',  )',
                      '\'constraints\' => array(\'pageid\' => \'[0-9]+\', \'expageid\' => \'[0-9;]+\',  )',
                     );

?>