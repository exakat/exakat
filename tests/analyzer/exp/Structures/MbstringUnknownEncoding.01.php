<?php

$expected     = array('mb_check_encoding($x, PHP_VERSION == \'7.3\' ? \'us\' : \'ISO-8859-144\')',
                      'mb_check_encoding($x, \'UTF9\')',
                     );

$expected_not = array('mb_check_encoding($x, "UTF8")',
                      'mb_check_encoding($x, \'UTF8\')',
                     );

?>