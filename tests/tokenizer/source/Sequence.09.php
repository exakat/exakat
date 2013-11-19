<?php
	$unzip = new SimpleUnzip();
	$backupdir = substr($datafile_server, 8, 13);
	$unzip->ReadFile($datafile_server);
?>
