<?php

$expected     = array('get_class($a->b)::$c', 
                      '\get_class($a->b)::C', 
                      'get_CLass($a->b)::m( )',
 );

$expected_not = array('$c->get_class($a->b)::$c',
                      'get_class($a->b)::class', // not compilable
                     );

?>