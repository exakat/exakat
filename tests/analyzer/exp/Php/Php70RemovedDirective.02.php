<?php

$expected     = array('ini_get("asp_tags")'
                     );

$expected_not = array('get_include_path( )', 
                      'set_include_path($new_path)', 
                     );

?>