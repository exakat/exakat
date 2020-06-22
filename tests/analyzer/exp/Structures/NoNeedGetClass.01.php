<?php

$expected     = array('get_class($a1->b)', 
                      '\get_class($a2->b)', 
                      '\get_class($a3->b)', 
                      'get_CLass($a4->b)',
                     );

$expected_not = array('$c->get_class($a->b)::$c',
                      'get_class($a->b)::class',
                     );

?>