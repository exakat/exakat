<?php

print "list all first-level files in projects for stats\n";
$res = shell_exec('find projects/*/code -mindepth 1 -maxdepth 1 -type f -ls');

$files = explode("\n", $res);
$files = array_map(function ($x) { return basename($x); }, $files);
$counts = array_count_values($files);

foreach($counts as $k => $v) {
    if ($v < 20) {
        unset($counts[$k]);
    }
}
asort($counts);
print_r($counts);


print "list all first-level folders in projects for stats\n";
$res = shell_exec('find projects/* -depth 2 -type d -path "*/code/*"');
print_r($res);

$stats = array();
$dirs = explode("\n", $res);
foreach($dirs as $dir) {
    $dir = basename($dir);
    $stats[] = $dir;
}

$stats = array_count_values($stats);
asort($stats);
print_r($stats);
?>