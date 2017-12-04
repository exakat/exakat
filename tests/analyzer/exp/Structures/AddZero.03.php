<?php

$expected     = array('$a->b += 0',
                      '$a->b += -0',
                      '$a->b += +0',
                     );

$expected_not = array('$a->b += (isset($r[0]->total->{1}) ? $r[0]->total->{1} : 0)',
                      '$a->b += isset($r[0]->total->{1}) ? $r[0]->total->{1} : 0',
                     );

?>