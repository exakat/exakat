<?php

$expected     = array('return $a[\'b\']++',
                     );

$expected_not = array('return $a->b++',
                      'return ++$a',
                     );

?>