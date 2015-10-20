<?php

$git = shell_exec('git status | grep modified | grep Analyzer | grep library');

$rows = explode("\n", trim($git));

$test = '';
$total = 0;
foreach($rows as $row) {
    if (preg_match('#Analyzer/(.+)/(.+)\.php#is', $row, $r)) {
        $test .= "phpunit Test/{$r[1]}_{$r[2]}.php\n";
        ++$total;
    } else {
        var_dump($row);
    }
}

file_put_contents('tests/analyzer/phpunit.sh', $test);
print "Generated $total tests\n";

?>