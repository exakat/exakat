<?php

$tests = glob('../../library/Analyzer/*/*.php');
$total = 0;
$missing = 0;
foreach($tests as $test) {
    $type = basename(dirname($test));
    $analyzer = basename($test);
    $analyzer = substr($analyzer, 0, -4);
    
    $files = glob('source/'.$type."_".$analyzer.".*.php");
    $total += count($files);
    if (count($files) == 0) {
        $missing ++;
    }
    print $type."_".$analyzer."   ".count($files)."\n";
    
}
print "Total : $total tests\n";
print "Missing : $missing tests\n";

?>