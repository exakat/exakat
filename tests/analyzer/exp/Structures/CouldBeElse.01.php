<?php

$expected     = array('if($a) { /**/ } ',
                      'if(!$b) { /**/ } ',
                     );

$expected_not = array('if($c) { /**/ } ',
                      'if(!$d) { /**/ } ',
                      'if($e) { /**/ } ',
                      'if($f) { /**/ } ',
                     );

?>