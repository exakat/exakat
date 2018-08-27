<?php
$connection = odbc_connect('Driver={SQL Server Native Client 10.0};Server=$server;Database=$database;', $user, $password);
odbc_reconnect($connection);

?>