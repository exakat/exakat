<?php

$expected     = array('throw $a->b[$c]( )',
                     );

$expected_not = array('$a->b[$c]',
                      'new $a->b[$c]( )',
                      'new $a->b[$d]',
                      '$e',
                     );

?>