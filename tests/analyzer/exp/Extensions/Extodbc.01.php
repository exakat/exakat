<?php

$expected     = array('odbc_connect(\'Driver={SQL Server Native Client 10.0};Server=$server;Database=$database;\', $user, $password)',
                     );

$expected_not = array('odbc_reconnect($connection)',
                     );

?>