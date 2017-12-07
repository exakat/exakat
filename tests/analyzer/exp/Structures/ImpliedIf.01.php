<?php

$expected     = array('$a or $b',
                      '$c || $d',
                      '$h && $i',
                     );

$expected_not = array('$e and $g instanceof y',
                      '($j && $k) xor $l',
                      '$j && $k',
                      '($j && $k)',
                      '($h && $i)',
                     );

?>