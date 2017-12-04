<?php

$expected     = array('(boolean) (($e <= 2))',
                      '(boolean) (($d <= 2))',
                      '(boolean) ($c < 2)',
                     );

$expected_not = array('(boolean) ($a + 2)',
                      '(integer) ($b > 2)',
                     );

?>