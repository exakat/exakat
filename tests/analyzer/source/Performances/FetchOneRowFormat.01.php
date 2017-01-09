<?php

// Almost the worse
$res->fetchRow();

// The worse
$res->fetchRow(SQLITE3_BOTH);
$res->fetchRow(\SQLITE3_BOTH);

// Those are OK
$res->fetchRow(SQLITE3_NUM);
$res->fetchRow(\SQLITE3_NUM);
$res->fetchRow(SQLITE3_ASSOC);
$res->fetchRow(\SQLITE3_ASSOC);

$res->fetchObject();
fetchRow(\SQLITE3_ASSOC);

?>