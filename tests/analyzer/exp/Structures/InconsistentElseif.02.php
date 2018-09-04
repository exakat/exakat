<?php

$expected     = array('if($e1 == 1) { /**/ } elseif($e1 == 2) { /**/ } elseif(A::$e1 == 3) { /**/ } elseif($e1 == 4) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if($a == 1) { /**/ } else { /**/ } ',
                      'if($b == 1) { /**/ } elseif($b->b == 2) { /**/ } else { /**/ } ',
                      'if($c == 1) { /**/ } elseif($c[3] == 2) { /**/ } elseif($c->d == 3) { /**/ } else { /**/ } ',
                      'if($d == 1) { /**/ } elseif($d[3] == 2) { /**/ } elseif($d->e == 3) { /**/ } elseif($o->{$d} == 4) { /**/ } else { /**/ } ',
                     );

?>