<?php

$expected     = array('function barx2($g) { /**/ } ',
                      'function barx3($g) { /**/ } ', 
                      'function barx4($g) { /**/ } ',
                     );

$expected_not = array('function barx($g) { /**/ } ',
                      'abstract function barx($g) { /**/ } ',
                      'function bari($g) { /**/ } ',
                      'function barx5($g) { /**/ } ',
                     );

?>