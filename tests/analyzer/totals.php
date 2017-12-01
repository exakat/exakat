<?php

$files = glob('./Test/*.php');

$res = shell_exec('grep " methods " Test/*/*.php | grep -v Skeleton');
$numbers = explode("\n", trim($res));

$numbers = array_map(function ($x) { preg_match('/ (\d+) method/', $x, $r); return $r[1]; }, $numbers);

print "Statistics \n";
print "======================== \n";
print count($files)." Tests\n";
print array_sum($numbers)." methods\n";
print number_format(array_sum($numbers) / count($numbers), 2)." on average\n";
print min($numbers)." minimum\n";
print max($numbers)." maximum\n\n";

// unfinished tests
$total = 0;
$files = glob('exp/*/*.php');
foreach($files as $file) {
    $php = file_get_contents($file);
    
    if ($a = preg_match_all("/,
                     \);/s", $php, $r) !== 2) {
        print "Missing coding convention $file ($a)\n";
    }
    
    try {
        include($file);
    } catch (Throwable $e) {
        echo "Exception: {$e->getMessage()}\n";
        continue;
    }
    
    if (empty($expected) && empty($expected_not)) {
        ++$total;
        print "Empty exp files : $file\n";
    } else {
        if (empty($expected)) {
//            ++$total;
            print "Empty expected array : $file\n";
        }

        if (empty($expected_not)) {
//            ++$total;
            print "Empty expected_not array : $file\n";
        }
    }
}
print "total unfinished tests : $total\n\n";

$total = 0;
$files = glob('source/*/*.php');
foreach($files as $file) {
    if (filesize($file) < 15) {
        ++$total;
        print "Empty source : $file\n";
    }
}
print "total unfinished tests : $total\n\n";

$total = 0;
$files = glob('../../library/Analyzer/*/*.php');

foreach($files as $file) {
    $analyze = basename($file);
    $folder = basename(dirname($file));
    if ($folder == 'Common') { continue; }

    $test = $folder.'/'.$analyze;
    if (!file_exists('Test/'.$test)) {
        ++$total;
        print "No test : $test\n";
    }
//    print $test;
//    die();
}
print "total untested class : $total\n\n";
die();
print shell_exec('find source -name "*.php" -print0 | xargs -0 -n1 -P8 php -l | grep -v "No syntax error"');
print shell_exec('find exp -name "*.php" -print0 | xargs -0 -n1 -P8 php -l | grep -v "No syntax error"');

?>