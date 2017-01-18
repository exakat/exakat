<?php

echo mysql_error();

die('Error '.pg_last_error()."\n");
die('Error '.pg_error()."\n"); // pg_error is not an error function

$imap = imap_open("{localhost:143}INBOX", "user_id", "password") or die (imap_errors());

$x = 'Error '.mysqli_error();

echo my_errors();

?>