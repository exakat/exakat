<?php

$expected     = array('cyrus_query($res, CYRUS_CONN_NONSYNCLITERAL)',
                      'CYRUS_CONN_NONSYNCLITERAL',
                     );

$expected_not = array('CYRUS_CONN_NONSYNCLITERALLY',
                      'cyrus_query($res, CYRUS_CONN_NONSYNCLITERALLY)',
                     );

?>