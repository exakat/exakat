<?php

$git = shell_exec('git status  | grep ../../library/Exakat/Analyzer/');
$lines = explode("\n", trim($git));

$test = '';
foreach($lines as $line) {
    preg_match('$/([^/]*?/[^/]*?.php)$', $line, $r);
    $test .=  "phpunit Test/$r[1]\n";
}

file_put_contents('git2test.sh', $test);

print count($lines)." tests prepared\n";