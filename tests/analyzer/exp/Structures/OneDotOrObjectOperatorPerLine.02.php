<?php

$expected     = array('$a4->p1->b->c',
                      '$a3->p1->b( )->c( )',
                      '$a2->p1->b( )->c',
                      '$a1->p1->b->c',
                     );

$expected_not = array('$f4->p->b->c',
                      '$f3->p->b( )->c( )',
                      '$f2->p->b( )->c',
                      '$f1->p->b->c',
                      '$a4->b(\'a\' . \'c\');',
                     );

?>