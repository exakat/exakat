<?php
$dbhandle = sqlite_open('mysqlitedb');
$query = sqlite_exec($dbhandle, "UPDATE users SET email='jDoe@example.com' WHERE username='jDoe'", $error);
if (!$query) {
    exit("Error in query: '$error'");
} else {
    echo 'Number of rows modified: ', sqlite_changes($dbhandle);
}
?>