<?php

$expected     = array('preg_match("/ye\\y/X", $string)',
                      'preg_match(\'/ye\y/\', $string)',
                      'preg_match("/ye\\J/X", $string)',
                      'preg_match(\'/ye\J/\', $string)',
                     );

$expected_not = array('preg_match(\'/ye\s/S\', $string)',
                      'preg_match(\'/ye\y/\', $string)',
                      'preg_match(\'/ye\s/X\', $string)',
                     );

?>