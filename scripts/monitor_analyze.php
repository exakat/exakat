<?php

$project = $argv[1];

$done = file('log/analyze.log');
array_shift($done);
array_shift($done);
array_shift($done);
array_shift($done);

foreach($done as $i => $v) {
    list($a, $t, $n) = split("\t", trim($v));
    $done[$i] = $a;
    $times[] = $t;
}

sort($times);
$eta = $times[floor(count($times) / 2)];

$config = parse_ini_file('projects/'.$project.'/config.ini');

//print_r($config['analyzer']);

$diff = array_diff($config['analyzer'], $done);

print_r($diff);
print count($diff) .' / '. count($config['analyzer'])." left (".number_format( count($diff) / count($config['analyzer']) * 100, 2)." %)\n";
$ceta = ($eta * count($diff));
if ($ceta < 60) {
    $peta = $ceta." s";
} elseif ($ceta < 3600) {
    $peta = floor($ceta / 60)." m ".floor($ceta - floor($ceta / 60) * 60)." s";
} else {
    $peta = $ceta." s";
}
print "ETA : ". $peta." ( ".date( 'r', time() + $ceta).") \n";

?>