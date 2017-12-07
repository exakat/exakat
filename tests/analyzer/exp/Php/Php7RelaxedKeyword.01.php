<?php

$expected     = array('B::then(1)',
                      'C::while(1, 2)',
                      'A::do( )',
                      'D::include(1, 2, 3, 4)',
                      '$b->foreach(1)',
                      '$d->include(1, 2, 3, 4)',
                      '$a->do( )',
                      '$c->while(1, 2)',
                     );

$expected_not = array('normal()',
                      'normalStatic(1)',
                     );

?>