<?php

$expected     = array('preg_split(\'/\\#/S\', $url)',
                      'preg_split(\'/\\#/sS\', $url)',
                     );

$expected_not = array('preg_split(\'\\#\', $url)',
                      'preg_split(\'/\\#/sx\', $url)',
                      'preg_split(\'/\#/\', $url)', 
                      'preg_split(\'\#\', $url)', 
                      'preg_split(\'/\#/sx\', $url)',
                     );

?>