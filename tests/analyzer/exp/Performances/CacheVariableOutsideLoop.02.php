<?php

$expected     = array('C::staticmethod($x->x)', 
                      'C::staticmethod($x->x + $a)', 
                      '$a->method(__DIR__)', 
                      '$a->method(__DIR__ . $a)',
                     );

$expected_not = array('C::staticmethod($x->x + $d)', 
                      'C::staticmethod($x->x + $e)', 
                      '$a->method(__DIR__ . $b)',
                     );

?>