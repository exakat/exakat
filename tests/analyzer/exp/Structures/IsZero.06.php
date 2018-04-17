<?php

$expected     = array('$a->b2 - ($a->b2)',
                      '$a->b3 - ($d = $a->b3)',
                      '$a->b4 - $d = $a->b4',
                      '$a->b1 - $a->b1',
                     );

$expected_not = array('$a[\'b\'] + 4 * $d["br$f"] - $previous["br$g"]',
                      '$x - $X',
                      '$a->b6 - $a->b6',
                      '$a->b5 - $a->b6 - $a->b6',
                     );

?>