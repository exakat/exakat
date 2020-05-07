<?php

$expected     = array('fn ($x, ...$rest) => $x',
                      'static fn ($y = 2, ...$rest) => $x',
                      'fn ($y = 3, ...$rest) => $y',
                     );

$expected_not = array('fn($y = 4, &$rest) => $y + $rest',
                     );

?>