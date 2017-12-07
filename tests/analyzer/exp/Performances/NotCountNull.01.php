<?php

$expected     = array('count($a1) == 0',
                      '\\count($a3) !== 0',
                      'strlen($a2) === 0',
                      'count($a5) > 0',
                      '\\strlen($a4) != 0',
                      'count($a7) < 0',
                      '0 > count($a6)',
                      'strlen($a8) >= 0',
                     );

$expected_not = array('count($a9) == -1',
                      '2 === strlen($a10)',
                      'count($a11) == -1',
                     );

?>