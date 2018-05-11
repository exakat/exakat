<?php

$expected     = array('strtr(\'abc\', \'ab\', "AB\\t")',
                      'strtr(\'abc\', \'abcd\', "AB\\t")',
                     );

$expected_not = array('strtr(\'abc\', \'abc\', "AB\\u{00a5}")',
                      'strtr(\'abc\', \'abc\', "AB\\090")',
                      'strtr(\'abc\', \'abc\', "AB\\t")',
                     );

?>