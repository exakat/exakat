<?php

$expected     = array('setlocale(\'LC_CTYPE\', 1)',
                      'setlocale(\'LC_\' . \'TIME\', 1)',
                      'setlocale(LC_MUNUTURY, 2)',
                     );

$expected_not = array('setlocale($x, 4)',
                      'setlocale($o->m(), 5)',
                      '$x->setlocale(LC_MONETARY, 1)',
                      'setlocale(LC_ALL, 1)',
                      'setlocale(\\LC_COLLATE, 1)',
                     );

?>