<?php

$expected     = array('(int) $_GET[\'x\'] === 2', 
                      '(int) $_GET[\'xc\'] === $c',
                      '(int) $_GET[\'xe\'] === $e',
                     );

$expected_not = array('(int) $_GET[\'xd\'] === $d',
                     );

?>