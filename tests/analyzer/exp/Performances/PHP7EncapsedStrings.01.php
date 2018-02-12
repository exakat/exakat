<?php

$expected     = array('\'a\' . $b',
                      '$c . \'d\'',
                      '$e->g . $f . \'A\'',
                      '$k[\'l\'] . $m[\'o\'] . \'A\'',
                     );

$expected_not = array('$e->g.$f.A::G2',
                      'g().\'a\'',
                      '$b->c().\'f\'',
                      '$e->g . A::$G',
                     );

?>