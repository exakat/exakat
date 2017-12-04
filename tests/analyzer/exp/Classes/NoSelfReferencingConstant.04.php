<?php

$expected     = array('II = self::II',
                      'II2 = self::II + 1',
                      'I3 = i::I3',
                      'I4 = \\i::I4',
                      'I32 = i::I32 . 3',
                      'I42 = \\i::I42 * 2',
                     );

$expected_not = array('X = 1',
                      'I45 = \'1\'',
                     );

?>