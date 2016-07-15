<?php

$args = $argv;

if (count($args) < 2) {
    print "Building exp/* for all tests\n";
}

@$file = $argv[1];
@$number = @$argv[2];

if (empty($file)) {
    $sources = glob('source/*.php');
    foreach($sources as $k => $v) {
        $sources[$k] = preg_replace('/(.*\.\d+)\..*$/', '\1', basename($v));
    }
    
    $exp = glob('exp/*.txt');
    foreach($exp as $k => $v) {
        $exp[$k] = preg_replace('/(.*\.\d+)\..*$/', '\1', basename($v));
    }
    
    $diff = array_diff($sources, $exp);
    
    foreach($diff as $d) {
        list($file, $d) = explode('.', $d);
        run($file, $d);
    }
} elseif (empty($number)) {
    $sources = glob('source/'.$file.'.*.php');
    foreach($sources as $k => $v) {
        $sources[$k] = preg_replace('/.*\.(\d+)\..*/', '\1', $v);
    }
    
    $exp = glob('exp/'.$file.'.*.txt');
    foreach($exp as $k => $v) {
        $exp[$k] = preg_replace('/.*\.(\d+)\..*/', '\1', $v);
    }
    
    $diff = array_diff($sources, $exp);

    foreach($diff as $d) {
        run($file, $d);
    }
} else {
    if (!file_exists('source/'.$file.'.'.$number.'.php')) {
        print "'$file.$number.php' doesn't exists. Aborting\n";
        die();
    }   
    run($file, $number);
}


function run($test, $number) {
    print "$test.$number\n";
    
    $shell = 'php -l ./source/'.$test.'.'.$number.'.php';
    $res = shell_exec($shell);
    
    if (strpos('No syntax errors detected in', $res) !== false) {
        print "This script doesn't compile with ".PHP_VERSION." .\n";
        return;
    }
    
    $shell = 'cd ../..; php exakat cleandb; php exakat load -f ./tests/tokenizer/source/'.$test.'.'.$number.'.php -p test; php exakat export -text -f ./tests/tokenizer/exp/'."$test.$number".'.txt';
    $res = shell_exec($shell);
    
    if (preg_match("/Warning : (.*?)\n/is", $res, $r) !== 0) {
        print "$test $number has some warning : $r[1]\n";
        return;
    }
    
    if (!file_exists('./exp/'."$test.$number".'.txt')) {
        print "This script has no exp file.\n";
        return;
    }

    if (filesize('./exp/'."$test.$number".'.txt') == 0) {
        unlink('./exp/'."$test.$number".'.txt');
        print "This script has an empty exp file.\n";
        return;
    }

    $exp = file_get_contents('./exp/'."$test.$number".'.txt');
    if (strpos($exp, 'Parse error') !== false) {
        print "This script doesn't compile.\n";
        return;
    }

    if (strpos($exp, 'Label : NEXT') !== false) {
        print "There are some unprocessed link in this script\n";
        return;
    }
}
?>