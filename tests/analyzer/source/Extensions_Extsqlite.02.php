<?php
$dbhandle = new SQLiteDatabase('mysqlitedb');
$query = $dbhandle->queryExec("UPDATE users SET email='jDoe@example.com' WHERE username='jDoe'", $error);
if (!$query) {
    exit("Error in query: '$error'");
} else {
    echo 'Number of rows modified: ', $dbhandle->changes();
}
?>