<?php

$a = dirname($someFilePath).'asb';
$a = dirname($someFilePath).'/sb';
$a = dirname($someFilePath)."as$b";
$a = dirname($someFilePath)."/s$b";

$a = 'a'.dirname($someFilePath, 2).'asc';
$a = 'a'.dirname($someFilePath, 2).'/sc';
$a = 'a'.dirname($someFilePath, 2)."as$c";
$a = 'a'.dirname($someFilePath, 2)."/s$c";

?>