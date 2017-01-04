<?php
$y = file_get_contents('/path/to/file.txt');
$x = unserialize(strval($y));
?>