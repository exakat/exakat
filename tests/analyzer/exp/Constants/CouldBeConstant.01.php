<?php

$expected     = array('$c[\'abcd\'] = \'ab\' . \'cd\'', 
                      '$b = "abcde"', 
                      '$b = "abcd"', 
                      '$c[\'abcd\']', 
                      '$c[\'abcde\']', 
                      '$c[\'abcde\'] = \'ab\' . \'cde\'', 
                      '$c[\'abc\']', 
                      '$c[\'abc\'] = \'ab\' . \'c\'', 
                      '$b = "abc"',
                     );

$expected_not = array('$x = <<<XX
abc{$c}abcd{$c}abcde{$d}abcdef
XX',
                     );

?>