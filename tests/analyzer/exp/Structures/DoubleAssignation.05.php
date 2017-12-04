<?php

$expected     = array('P::$c2 = C(\'D\', P::$c3)',
                      '$b->AD2 = C(\'D\', $b->AD4)',
                      '$b[\'B2\'] = C(\'D\', $b[\'B\'])',
                     );

$expected_not = array('P::$c = C(\'D\', P::$c)',
                      '$b->AD = C(\'D\', $b->AD)',
                      '$b[\'B\'] = C(\'D\', $b[\'B\'])',
                     );

?>