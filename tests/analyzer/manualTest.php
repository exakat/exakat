<?php

if (!isset($argv[1])) {
    die("Usage : php manualTest.php ./Test/Structures/Test.php [01]\n");
}

$testFile = $argv[1];
// Special case for phpunit like presentation
if (preg_match('/--filter=(\d\d)/', $testFile, $r)) {
    $number = $r[1];
    $testFile = $argv[2];
}

if (substr($testFile, -4) != '.php') {
    $testFile .= '.php';
}
if (substr($testFile, 0, 2) != './') {
    $testFile = './'.$testFile;
}
if (!file_exists($testFile)) {
    die($testFile." doesn't exist\n");
}

if (isset($number)) {
    // Nothing to do
} elseif (isset($argv[2])) {
    $number = substr('00'.((int) $argv[2]), -2);
} else {
    $number = '01';
}

$sourceFile = str_replace('.php', '.'.$number.'.php', str_replace('/Test/', '/source/', $testFile));
if (!file_exists($sourceFile)) {
    $numbers = array_map(function ($x) {return substr($x, -6, -4); }, glob(str_replace('.'.$number.'.', '.*.', $sourceFile)));
    if (count($numbers) === 1) {
        print "No such test as $number. Using 01\n";
        $number = '01';
        $sourceFile = str_replace('.php', '.'.$number.'.php', str_replace('/Test/', '/source/', $testFile));
    } else {
        die($sourceFile." doesn't exist. One of ".join(', ', $numbers).".\n");
    }
}

$expFile = str_replace('/source/', '/exp/', $sourceFile);
if (is_dir('../../test.php')) {
    $files = glob('../../test.php/*');
    foreach($files as $file) {
        unlink($file);
    }
    rmdir('../../test.php');
} else {
    unlink('../../test.php');
}

if (is_dir($sourceFile)) {
    mkdir('../../test.php', 0755);
    $files = glob($sourceFile.'/*');
    foreach($files as $file) {
        copy($file, '../../test.php/'.basename($file));
    }
} else {
    print "$sourceFile\n";
    copy($sourceFile, '../../test.php');
}

$test = substr($testFile, 7, -4);

shell_exec('bbedit '.$sourceFile);
shell_exec('bbedit '.$expFile);
shell_exec('bbedit ../../test.php');

$analyzerFile = '../../library/Exakat/Analyzer/'.substr($testFile, 7);
shell_exec('bbedit '.$analyzerFile);


$sh = file_get_contents('../../test.sh');
$sh = preg_replace('#-p test -P .*?/.*? -#is', '-p test -P '.$test.' -', $sh);
file_put_contents('../../test.sh', $sh);


print "Run all  unit tests with : phpunit $testFile\n";
print "Run this unit tests with : phpunit --filter=$number $testFile\n";
$res = shell_exec('php -l '.$sourceFile);
print "Lint           ".(trim($res) == "No syntax errors detected in $sourceFile" ? 'OK' : 'KO')."        : php -l $sourceFile\n";
$res = shell_exec('php -l '.$expFile);
print "Lint           ".(trim($res) == "No syntax errors detected in $expFile" ? 'OK' : 'KO')."        : php -l $expFile\n";

?>