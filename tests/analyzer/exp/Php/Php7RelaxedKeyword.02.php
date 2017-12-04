<?php

$expected     = array('$C->While(1, 2)',
                      '$A->Do( )',
                      '$D->Include(1, 2, 3, 4)',
                      '$B->Foreach(1)',
                      'B::Then(1)',
                      'C::While(1, 2)',
                      'A::Do( )',
                      'D::Include(1, 2, 3, 4)',
                     );

$expected_not = array('Normal()',
                      'Normalstatic(1)',
                     );

?>