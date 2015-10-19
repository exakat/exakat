<?php

$project = 'ask';

$files = glob('projects/'.$project.'/report/ajax/*.html');

$totalLink = 0;
$totalFile = 0;
$totalMiss = 0;

foreach($files as $file) {
    ++$totalFile;
    $html = file_get_contents($file);
    
    preg_match_all('$href="\#?(.*?)"$is', $html, $r);
    
    $links = array_filter($r[1], function ($x) {
        if (empty($x)) { return false; }
        
        if (substr($x, 0, 4) == 'http') { return false; }
        if (substr($x, 0, 6) == 'mailto') { return false; }
        
        return true;
    });
    
    $leftLinks = array_filter($links, function ($x) use ($project) {
        return !file_exists('projects/'.$project.'/report/'.$x);
    });
    
    $totalLink += count($links) - count($leftLinks);
    $totalMiss += count($leftLinks);
    if (count($leftLinks) != 0) {
        print count($leftLinks) . ' are missing in '.$file."\n";
        print_r($leftLinks);
    }
}

print "Total files  : $totalFile\n";
print "Total links  : $totalLink\n";
print "Total missed : $totalMiss\n";

?>