<?php

$expected     = array('new \a\xx( )', 
                      'new xx', 
                      'new \a\xx( )', 
                      'new xx( )',
                     );

$expected_not = array('new \a\XX( )', 
                      'new XX', 
                      'new \a\XX( )', 
                      'new XX( )',
                     );

?>