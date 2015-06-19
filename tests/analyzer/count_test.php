<?php

$files = glob('Test/*.php');

$ignoreList = array('Test/Skeleton.php', 'Test/Analyzer.php');
foreach($ignoreList as $i) {
    $id = array_search($i, $files);
    unset($files[$id]);
}

print count($files)." classes in Test folder\n";

$shell = shell_exec('grep " methods" Test/*');
$lines = split("\n", trim($shell));

$total = 0;
foreach($lines as $line) {
    preg_match('#(\d+) methods#is', $line, $r);
    $total += $r[1];
}

print "$total methods in Test folder\n";

?>