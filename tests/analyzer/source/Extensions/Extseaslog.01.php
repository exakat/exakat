<?php

$lastLogger_1 = SeasLog::getLastLogger();

SeasLog::setLogger('testModule/app1');
$lastLogger_2 = SeasLog::getLastLogger();

var_dump($lastLogger_1,$lastLogger_2);
/*
string(7) "default"
string(15) "testModule/app1"
*/

SeasLog_getLastLogger();


?>