<?php

$expected     = array('new \xx', 
                      'new xx', 
                      'new \xx( )', 
                      'new xx( )',
                     );

$expected_not = array('new \XX', 
                      'new XX', 
                      'new \XX( )', 
                      'new XX( )',
                     );

?>