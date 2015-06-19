<?php
    $analyzers = glob('../library/Analyzer/*/*.php');
    
    foreach($analyzers as $a) {
        if (basename(dirname($a)) == 'Common') { continue; }
        $base = basename(dirname($a)).'_'.basename(substr($a, 0, -4));
        
        $tests = glob('source/'.$base.'.*.php');
        
        print $base.' '.count($tests)."\n";
    }
?>