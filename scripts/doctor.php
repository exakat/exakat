<?php

$stats = array();

// check PHP
$stats['PHP']['version'] = phpversion();

// wkhtmltopdf
shell_exec('whereis wkhtmltopdf');

foreach($stats as $section => $details) {
    print "$section : \n";
    foreach($details as $k => $v) {
        print "    ".$k." : ".$v."\n";
    }
}

?>