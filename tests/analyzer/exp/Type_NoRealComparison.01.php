<?php

$expected     = array('$a == 1.2',
                      '3.4 != $b',
                      '1.23 === 4.55',
                      '5.6 + \'c\' !== $b',
);

$expected_not = array('$d == 3',
                      '$e = 7.9'
);

?>