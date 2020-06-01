<?php

$expected     = array('fn (A $b) : int => $b->c',
                      'fn (A $b) => $b->c',
                     );

$expected_not = array('$e->fn( )',
                     );

?>