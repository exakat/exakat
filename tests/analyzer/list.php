<?php

print "\n";
if (in_array('-0',$argv) !== false) {
    define('DISPLAY_0', true);
} else {
    define('DISPLAY_0', false);
}

$tests = glob('../../library/Analyzer/*/*.php');
$total = 0;
$missing = 0;
foreach($tests as $test) {
    $type = basename(dirname($test));
    if ($type == 'Common') { continue; }
    if ($type == 'Themes') { continue; }
    $analyzer = basename($test);
    $analyzer = substr($analyzer, 0, -4);
    
    $files = glob('source/'.$type."_".$analyzer.".*.php");
    $total += count($files);
    if (count($files) == 0) {
        $missing ++;
    }
    if (!DISPLAY_0 || count($files) == 0) {
        print $type."/".$analyzer."   ".count($files)."\n";
    }
}

print "\n";
print "Total : $total tests\n";
print "Missing : $missing tests\n";

$tests = glob('Test/*.php');
$total = 0;
$toomuch = 0;
foreach($tests as $test) {
    $type = substr($test, 5, -4);
    if ($type == 'Analyzer') { continue; }
    if ($type == 'Skeleton') { continue; }
    if (!file_exists('../../library/Analyzer/'.str_replace('_', '/', $type).'.php')) {
        print "- $type\n";
        $toomuch++;
    }
    
    $code = file_get_contents($test);
    preg_match('#(\d+) methods#is', $code, $r);
    if ($r[1] == 0) {
        print "- $type\n";
    }
    
    $total += $r[1];
}

if ($toomuch) {
    print "Extra : $toomuch tests\n";
}
print "$total methods\n";

?>