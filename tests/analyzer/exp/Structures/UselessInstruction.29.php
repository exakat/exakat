<?php

$expected     = array('$a ?? null',
                      '$b ?? null',
                     );

$expected_not = array('$b[3] ?? \'\'',
                      '$a ?? ($b ?? null)',
                      '$a ?? ($b ?: null)',
                      '$a ?? ($d ? 3 : null)',
                     );

?>