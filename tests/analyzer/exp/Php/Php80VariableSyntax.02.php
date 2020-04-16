<?php

$expected     = array('new ($expression)',
                      '$x instanceof ($y . $z)',
                     );

$expected_not = array('new $variable',
                      '$x instanceof $y',
                     );

?>