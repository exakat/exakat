<?php

$expected     = array('(string) $b ? 3 : 5',
                      '(string) ($b) ? 3 : 7',
                      '(string) $b ?: 8',
                      '(string) $b ?? 9',
                     );

$expected_not = array('(string) ($b ? 3 : 4)',
                      '((string) $b) ? 3 : 6',
                     );

?>