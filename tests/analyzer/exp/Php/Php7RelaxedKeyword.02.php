<?php

$expected     = Array( '$C->While(1, 2)', 
                       '$A->Do( )', 
                       '$D->Include(1, 2, 3, 4)', 
                       '$B->Foreach(1)', 
                       'B::Then(1)', 
                       'C::While(1, 2)', 
                       'A::Do( )', 
                       'D::Include');

$expected_not = Array('Normal()',
                      'Normalstatic(1)');

?>