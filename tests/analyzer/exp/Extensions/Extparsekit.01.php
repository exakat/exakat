<?php

$expected     = array('parsekit_compile_string(\'
echo "Foo\\n";
\', $errors, PARSEKIT_QUIET)',
                      'PARSEKIT_QUIET',
                     );

$expected_not = array('var_dump($PARSEKIT_QUIET)',
                     );

?>