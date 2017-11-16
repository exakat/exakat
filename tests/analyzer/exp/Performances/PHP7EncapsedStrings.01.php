<?php

$expected     = array('\'a\' . $b',
                      '$c . \'d\'',
                      '$e->g . $f . A::$G',
                     );

$expected_not = array('$e->g.$f.A::G2',
                      'g().\'a\'',
                      '$b->c().\'f\'',
                     );

?>