<?php

$expected     = array('\\a\\b\\c\\d\\e\\f(1)',
                     );

$expected_not = array('c\\d\\e\\f( )',
                      'a\\b\\c\\d\\e\\f(0)',
                      '$a($b)',
                      'D( )[\'E\']',
                      '$e->r[$f]($g->g->e( ), $f, $g, $x, $q->w)',
                     );

?>