<?php

$expected     = array('\get_class($a1->b)', 
                      'bar($a2->b)', 
                      'get_class($a3->b)',
                     );

$expected_not = array('get_class($d)',
                     );

?>