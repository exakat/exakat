<?php

$log = file_get_contents('alltests.txt');
$log = file_get_contents('randomtest.txt');

preg_match_all('/\d+\) Test\\\\([^:]+)::/', $log, $r);

$stats = array_count_values($r[1]);
asort($stats);

print_r($stats);

print "total = ".count($r[1])." \n";
print "distinct = ".count($stats)." \n";


?>