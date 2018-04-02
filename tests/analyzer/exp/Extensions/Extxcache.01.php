<?php

$expected     = array('xcache_isset("count")',
                      'xcache_inc("count")',
                      'xcache_set("count", load_count_from_mysql( ))',
                     );

$expected_not = array('XCACHE_NOT_A_CONSTANT',
                     );

?>