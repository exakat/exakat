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
    if ($type == 'Themes')  { continue; }
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

?>