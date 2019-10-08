<?php

$expected     = array('\' c \' => 4',
                      '\' c\' => 2',
                      '\'c \' => 3',
                      'case \' b \' :  /**/  ',
                      'case \'b \' :  /**/  ',
                      'case \' b\' :  /**/  ',
                      '$a[\' a\']',
                      '$a[\' a \']',
                      '$a[\'a \']',
                     );

$expected_not = array('\' c\' => 2',
                      'case \' b\' :  /**/  ',
                      '$a[\' a\']',
                     );

?>