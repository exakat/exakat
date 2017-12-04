<?php

$expected     = array('"/[ab$fe]+/"',
                      '\'/[abc]+/\'',
                      '\'/[ab\' . \'e]+/\'',
                      '\'/[abd]+/\'',
                     );

$expected_not = array('$f[1]',
                      '\'/[abe]+/\'',
                     );

?>