<?php

$stats = array();

// check PHP
$stats['php']['version'] = phpversion();

// check PHP 5.2
$stats['PHP 5.2']['version'] = shell_exec('php52 -r "echo phpversion();" 2>&1');

// check PHP 5.3
$stats['PHP 5.3']['version'] = shell_exec('php53 -r "echo phpversion();" 2>&1');

// check PHP 5.4
$stats['PHP 5.4']['version'] = shell_exec('php54 -r "echo phpversion();" 2>&1');

// check PHP 5.5
$stats['PHP 5.5']['version'] = shell_exec('php55 -r "echo phpversion();" 2>&1');

// check PHP 5.6
$stats['PHP 5.6']['version'] = shell_exec('php56 -r "echo phpversion();" 2>&1');

// wkhtmltopdf
$res = shell_exec('wkhtmltopdf --version 2>&1');
if (preg_match('/command not found/is', $res)) {
    $stats['wkhtmltopdf']['installed'] = 'No';
} elseif (preg_match('/wkhtmltopdf\s+([0-9\.]+)/is', $res, $r)) {
    $stats['wkhtmltopdf']['installed'] = 'Yes';
    $stats['wkhtmltopdf']['version'] = $r[1];
} else {
    $stats['wkhtmltopdf']['error'] = $res;
}

// phploc
// wkhtmltopdf
$res = shell_exec('phploc --version 2>&1');
if (preg_match('/command not found/is', $res)) {
    $stats['phploc']['installed'] = 'No';
} elseif (preg_match('/phploc\s+([0-9\.]+)/is', $res, $r)) {
    $stats['phploc']['installed'] = 'Yes';
    $stats['phploc']['version'] = $r[1];
} else {
    $stats['phploc']['error'] = $res;
}

foreach($stats as $section => $details) {
    print "$section : \n";
    foreach($details as $k => $v) {
        print "    ".$k." : ".$v."\n";
    }
    print "\n";
}

?>