<?php

$expected     = array('mb_strlen($a2) === 0',
                      '\\mb_strlen($a4) != 0',
                      'mb_strlen($a8) >= 0',
                     );

$expected_not = array('2 === mb_strlen($a10)',
                     );

?>