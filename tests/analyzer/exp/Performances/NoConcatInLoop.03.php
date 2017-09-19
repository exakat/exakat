<?php

$expected     = array('foreach($a as $b) { /**/ } ',
                      'for($i = 0 ; $i < 10 ; $i++) { /**/ } ',
                      );

$expected_not = array('foreach($a2 as $b2) { /**/ } ',
                      'for($i2 = 0 ; $i2 < 10 ; $i2++) { /**/ } ',
                      );

?>