<?php

$git = shell_exec('git status');
$lines = explode("\n", trim($git));

$test = array();
$total = 0;
foreach($lines as $line) {
    if (!preg_match('$modified:   (exp|source)/$', $line)) { continue ; }
    ++$total;
    preg_match('$/([^/]*?/[^/]*?)\.\d\d\.php$', $line, $r);
    $test []=  "php pu Test/$r[1].php\n";
}

$test = array_unique($test);

file_put_contents('git2test.sh', implode('', $test));

print count($test)."/$total tests prepared\n";