<?php

$expected     = array('$a->store_result( )', 
                      '$a->ping( )', 
                      '$a->error_list', 
                      '$a->affected_rows',
                     );

$expected_not = array('$b->affected_rows',
                      '$a->info()',
                      'mysqli::poll( )',
                     );

?>