<?php

$res = shell_exec('grep -r NEXT exp');
$rows = explode("\n", trim($res));

if (count($rows) == 1) {
    print "No NEXT found. Clean\n";
    die();
}

$files = array();
foreach($rows as $row) {
    list($file, ) = explode(':', $row);
    if (empty($file)) { continue; }
    $files[$file] = 0;
}

foreach($files as $file => $foo) {
    preg_match('#exp/(\w+).(\d\d).txt#', $file, $r);
    print "php prepareexp.php $r[1] $r[2]\n";
}
?>