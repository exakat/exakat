<?php

$args = $argv;

if (count($args) < 2) {
    print "Usage : prepareexp.php Test Number\n Aborting\n";
    die();
}

$file = $argv[1];
$number = @$argv[2];

if ($number == 0) {
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
    $shell = 'cd ../; php bin/delete -all; php ./bin/load -f ./tests/source/'."$test.$number".'.php; php ./bin/tokenizer; php ./bin/export -text -o ./tests/exp/'."$test.$number".'.txt';
    
    print shell_exec($shell);
    
    $exp = file_get_contents('./exp/'."$test.$number".'.txt');
    if (strpos($exp, 'Parse error') !== false) {
        print "This script doesn't compile.\n";
    }
    if (strpos($exp, 'Label : NEXT') !== false) {
        print "There are some unprocessed link in this script\n";
    }
}
?>