<?php

echo mysql_error();

die('Error '.pg_error()."\n");

$imap = ("{localhost:143}INBOX", "user_id", "password") or die imap_errors();

$x = 'Error '.mysqli_error();

?>