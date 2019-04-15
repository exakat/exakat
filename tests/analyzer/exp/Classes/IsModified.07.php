<?php

$expected     = array('$b->c',
                      '$c->d',
                      '$c->b',
                      '$b::$c',
                      '$c::$b',
                      '$c::$d',
                     );

$expected_not = array('$c',
                     );

?>