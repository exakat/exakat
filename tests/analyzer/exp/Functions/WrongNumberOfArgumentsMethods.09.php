<?php

$expected     = array('(new ax)->a( )',
                      '(new ax)->c( )',
                      '(new ax)->c(1)',
                      '(new x)->c(1)',
                      '(new x)->c( )',
                      '(new x)->a( )',
                     );

$expected_not = array('(new x)->a(1)',
                      '(new x)->a(1, 2)',
                      '(new x)->c(1, 2)',
                      '(new ax)->a(1)',
                      '(new ax)->a(1, 2)',
                      '(new ax)->c(1, 2)',
                      '(new y)->a(1)',
                      '(new y)->a(1, 2)',
                      '(new y)->c(1, 2)',
                      '(new y)->c(1)',
                      '(new y)->c( )',
                      '(new y)->a( )',
                     );

?>