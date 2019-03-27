<?php

$projects = array('a/b/c.json', 'a/c/d.ini');

$b = array_map(function ($fileOK) { return basename($fileOK, '.json');}, $projects);
print_r($b);
$c = array_map(function ($fileKO) { return basename($fileKO);}, $projects);
print_r($c);
$d = array_map('basename', $projects, array_fill(0, 2, '.json'));
print_r($d);
$c = array_map(function ($fileOK2) { return basename($fileOK2[2]);}, $projects);
print_r($c);

?>