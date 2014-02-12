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

// checking fullcode.log
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

if (preg_match_all('/\s([\w\/]*)\s*0/is', $res, $R)) {
    print count($R[1])." total analyzer without tests\n";
    print "  + ".join("\n  + ", $R[1])."\n\n";
} else {
    print "All analyzers have tests\n";
}


// checking fullcode.log

$tokens = 0;
$indexed = array();
$next = array();
$files = glob('projects/*/log/stat.log');
foreach($files as $file) {
    $log = file_get_contents($file);
    if (preg_match('/INDEXED_count : (\d+)/', $log, $R) && $R[1] != 0) {
        $indexed[] = $file." ({$R[0]})";
    }

    if (preg_match('/NEXT_count : (\d+)/', $log, $R) && $R[1] != 0) {
        $next[] = $file." ({$R[0]})";
    }

    if (preg_match('/tokens_count : (\d+)/', $log, $R) && $R[1] != 0) {
        $tokens += $R[1];
    }
}

if ($indexed) {
    print count($indexed)." stat.log have INDEXED\n";
    print "  + ".join("\n  + ", $indexed)."\n\n";
} else {
    print "All ".count($files)." stat.log are free of INDEXED\n";
}

if ($next) {
    print count($next)." stat.log have NEXT\n";
    print "  + ".join("\n  + ", $next)."\n\n";
} else {
    print "All ".count($files)." stat.log are free of NEXT\n";
}

print count($files)." projects collecting ".number_format($tokens,0)." tokens\n";

$files = glob('projects/*/');
$sqlite_md = array();
$report_md = array();
foreach($files as $file) {
    if ($file == 'projects/default/') { continue; }
    if ($file == 'projects/tests/') { continue; }
    
    if (!file_exists($file.'report.md')) {
        $report_md[] = $file;
    }

    if (!file_exists($file.'report.sqlite')) {
        $sqlite_md[] = $file;
    }
}

if ($report_md) {
    print count($report_md)." projects are missing markdown export\n";
    print "  + ".join("\n  + ", $report_md)."\n\n";
} else {
    print "All ".count($report_md)." projects have the markdown export\n";
}

if ($sqlite_md) {
    print count($sqlite_md)." projects are missing sqlite export\n";
    print "  + ".join("\n  + ", $sqlite_md)."\n\n";
} else {
    print "All ".count($files)." projects have the sqlite export\n";
}

?>