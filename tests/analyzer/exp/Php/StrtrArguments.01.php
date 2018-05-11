<?php

$expected     = array('strtr(\'abc\', \'abcd\', \'\')',
                      'strtr(\'abc\', \'abc\', \'ABCE\')',
                      'strtr(\'abc\', \'abcd\', \'ABC\')',
                     );

$expected_not = array('strtr(\'abc\', \'abc\', \'ABC\')',
                      'strtr(\'abc\', \'abcde\', \'\')',
                     );

?>