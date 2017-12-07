<?php

$expected     = array('$traversable3 instanceof c2',
                      '$traversable2 instanceof i2',
                      '$traversable2 instanceof \\i3',
                      '$traversable3 instanceof \\c3',
                      '$traversable instanceof \\Traversable2',
                     );

$expected_not = array('$traversable instanceof i',
                      '$traversable instanceof c',
                      '$traversable instanceof \\Traversable',
                     );

?>