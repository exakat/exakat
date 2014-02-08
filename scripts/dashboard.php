<?php

// report errorlog problems
$count = trim(shell_exec('ls -hla projects/*/log/errors.log| wc -l '));

$r = shell_exec('ls -hla projects/*/log/errors.log| grep -v 176 ');
if ($c = preg_match_all('/project(\S*)/', $r, $R)) {
    print "$c error.log are wrong\n";
    print "  + ".join("\n  + ", $R[0])."\n\n";
    print "Total of $count error.logs\n";
} else {
    print "All $count error.logs are OK\n";
}
print "\n\n";

$errors = array();
$Class_tmp = array();
$files = glob('projects/*/log/fullcode.log');
foreach($files as $file) {
    $log = file($file);
    $R = preg_grep('/Left token	(\d+)$/', $log);
    $last = substr(array_pop($R), 11) + 0 ;
    
    if ($last != 0) {
        $errors[] = $file." ($last)";
    }
    
    // spot Class_tmp
    $R = preg_grep('/Class_tmp	(\d+)$/', $log);
    $last = substr(array_pop($R), 10) + 0 ;
    
    if ($last != 0) {
        $Class_tmp[] = $file." ($last)";
    }
}

if ($errors) {
    print count($errors)." fullcode.log are wrong\n";
    print "  + ".join("\n  + ", $errors)."\n\n";
    print "Total of $count error.logs\n";
} else {
    print "All ".count($files)." fullcode.log are OK\n";
}
print "\n\n";

if ($Class_tmp) {
    print count($Class_tmp)." fullcode.log have Class_tmp\n";
    print "  + ".join("\n  + ", $Class_tmp)."\n\n";
} else {
    print "All ".count($files)." fullcode.log are free of Class_tmp\n";
}

$res = shell_exec('cd tests/analyzer/; php list.php');
preg_match_all('/\s(\w*)\s*(\d+)/is', $res, $R);
print array_sum($R[2])." total analyzer tests\n";

if (preg_match_all('/\s(\w*)\s*0/is', $res, $R)) {
    print count($R[1])." total analyzer without tests\n";
    print "  + ".join("\n  + ", $R[1])."\n\n";
} else {
    print "All analyzers have tests\n";
}


$tokens = array();
$indexed = array();
$files = glob('projects/*/log/stat.log');
foreach($files as $file) {
    $log = file($file);
    $R = preg_grep('/Left token	(\d+)$/', $log);
    $last = substr(array_pop($R), 11) + 0 ;
    
    if ($last != 0) {
        $errors[] = $file." ($last)";
    }
    
    // spot Class_tmp
    $R = preg_grep('/Class_tmp	(\d+)$/', $log);
    $last = substr(array_pop($R), 10) + 0 ;
    
    if ($last != 0) {
        $Class_tmp[] = $file." ($last)";
    }
}



?>