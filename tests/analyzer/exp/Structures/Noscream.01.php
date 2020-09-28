<?php

$expected     = array('@$noScream',
                      '@opendir(\'.\')',
                     );

$expected_not = array('@',
                      '@fopen($a, \'r\')',
                      '@\\fopen($a, \'r\')',
                     );

?>