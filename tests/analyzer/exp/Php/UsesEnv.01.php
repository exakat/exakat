<?php

$expected     = array('$_ENV',
                      'getenv("uniqid")',
                      'putenv("UNIQID=$uniqid")',
                     );

$expected_not = array('$ENV',
                     );

?>