<?php

$expected     = array('preg_match(\'/three times/i\', $_GET[\'x\'])',
                      'preg_match(\'/three times/i\', $GLOBALS[\'x\'])',
                      'preg_match(\'/three times/i\', $_GET[\'x\'])',
                      'preg_match(\'/twice/i\', $row[\'name\'])',
                      'preg_match(\'/twice/i\', $_GET[\'x\'])',
                     );

$expected_not = array('preg_match(\'/^circle|^\'.$x.\'$/i\', $string)',
                      'preg_match(\'/^circle|^square$/i\', $string)',
                     );

?>