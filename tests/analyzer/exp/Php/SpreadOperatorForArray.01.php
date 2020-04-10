<?php

$expected     = array('array(...$b)',
                      '[1, 2, 3, ...foo( )]',
                     );

$expected_not = array('[$a, $b]',
                      '$a->array(...C)',
                     );

?>