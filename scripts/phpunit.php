<?php

if (array_search('-t', $argv)) {
    echo "Running project test for tokenizer\n",
         "Getting recent code\n";
    shell_exec('rm -rf ./projects/test/code/*');
    shell_exec('cp ./tests/tokenizer/source/_* ./projects/test/code');
    shell_exec('cp ./tests/analyzer/source/[A-Z]* ./projects/test/code');
    shell_exec('cp ./tests/tokenizer/source/[A-Z]* ./projects/test/code');

    echo "Running the project on test\n";
    shell_exec('php bin/project test');
} else {
    echo "Not running project test\n";
}

echo "Running UT for analyzer\n";
$row = array('date' => '"'.date('Y-m-d H:i:s').'"', 'id' => 'NULL');

$begin = microtime(true);

if (array_search('-a', $argv)) {
    echo "Running ALL tests\n";
    shell_exec('cd tests/analyzer; phpunit randomtest.php > phpunit.txt');
    $results = file_get_contents('tests/analyzer/phpunit.txt');
} else {
    echo "Running random tests\n";
    shell_exec('cd tests/analyzer; phpunit randomtest.php > randomtest.txt');
    $results = file_get_contents('tests/analyzer/randomtest.txt');
}
$end = microtime(true);

$row['duration'] = ($end - $begin);
//Tests: 265, Assertions: 799, Failures: 77, Skipped: 7.

if (preg_match('/Tests: (\d+), Assertions: (\d+), Failures: (\d+)[\.,]/is', $results, $R)) {
    $row['tests'] = $R[1];
    $row['assertions'] = $R[2];
    $row['fails'] = $R[3];
} elseif (preg_match('/OK \((\d+) tests?, (\d+) assertions?\)/is', $results, $R)) {
    $row['tests'] = $R[1];
    $row['assertions'] = $R[2];
    $row['fails'] = 0;
} else {
    var_dump($results);
}

$mysql = new mysqli('localhost', 'root', '', 'exakat');
$query = "INSERT INTO unittests (`".implode("`, `", array_keys($row))."`) VALUES (".implode(", ", array_values($row)).")";
$mysql->query($query);
    
echo $row['tests']." tests ran : {$row['fails']} failed (".number_format($row['fails'] / $row['tests'] * 100, 2)." %)\n";

?>