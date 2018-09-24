<?php

$expected     = array('apc_store(\'anumber\', 42)',
                      'apc_fetch(\'anumber\')',
                      'apc_dec(\'anumber\')',
                      'apc_dec(\'anumber\', 10)',
                      'apc_dec(\'anumber\', 10, $success)',
                      'apc_store(\'astring\', \'foo\')',
                      'apc_dec(\'astring\', 1, $fail)',
                     );

$expected_not = array('apc_stores(\'foo\', \'bar\')',
                     );

?>