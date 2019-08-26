<?php

$expected     = array('$a[3][]{3}',
                      '$a{5}',
                      '$a{2}',
                      '$a[]{3}',
                      '$a{4}',
                      '$a{4}[]{3}',
                     );

$expected_not = array('$a[1]',
                      '$a[6][][3]',
                     );

?>