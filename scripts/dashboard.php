<?php

include_once(dirname(__DIR__).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_library');

// report errorlog problems
$count = trim(shell_exec('ls -hla projects/*/log/errors.log| wc -l '));

$r = shell_exec('ls -hla projects/*/log/errors.log| grep -v 191 | grep -v 176 ');
if ($c = preg_match_all('/project(\S*)/', $r, $R)) {
    print "$c error.log are wrong\n";
    print "  + ".join("\n  + ", $R[0])."\n\n";
    print "Total of $count error.logs\n";
} else {
    print "All $count error.logs are OK\n";
}
print "\n";

$res = shell_exec('cd tests/analyzer/; php list.php -0');
preg_match('/Total : (\d+) tests/is', $res, $R);
$total_UT = $R[1];
print $total_UT." total analyzer tests\n";

preg_match_all('/\s(\w*)\s*(\d+)/is', $res, $R);

if (preg_match_all('/(\w+\/\w+)\s*0/is', $res, $R)) {
    print count($R[1])." total analyzer without tests\n";
    print "  + ".join("\n  + ", $R[1])."\n\n";
} else {
    print "All analyzers have tests\n";
}

if (!file_exists('tests/analyzer/phpunit.txt')) {
    print "No recent unit test on Analyzers! Please, run php scripts/phpunit.php\n";
} elseif (time() - filemtime('tests/analyzer/phpunit.txt') > 86400) {
    print "Phpunit test is more than a day! Please, run php scripts/phpunit.php\n";
} else {
    $results = file_get_contents('tests/analyzer/phpunit.txt');

    if (preg_match('/Tests: (\d+), Assertions: (\d+), Failures: (\d+), Skipped: (\d+)\./is', $results, $R)) {
        preg_match_all('/\d+\) Test\\\\(\w+)::/is', $results, $R2);
        print "There were {$R[1]} failures in ".count(array_unique($R2[1]))." tests! Check the tests! \n";
        print "  + ".join("\n  + ", array_unique($R2[1]))."\n\n";
        
        if ($R[1] != $total_UT) {
            print "Not all the tests were run! Only {$R[1]} out of $total_UT. Please, run php scripts/phpunit.php\n";
        } else {
            print "All tests where recently run, some are KO\n";
        }
    } elseif (preg_match('/OK \((\d+) test, (\d+) assertions\)/is', $results, $R)) {
        if ($R[1] != $total_UT) {
            print "Not all the tests were run! Only {$R[1]} out of $total_UT. Please, run php scripts/phpunit.php\n";
        } else {
            print "All tests where recently run and OK\n";
        }
    } else {
        print "Nothing found in the unit tests!\n";
    }
}
print "\n";

$tokens = 0;
$indexed = array();
$next = array();
$fullcode = array();
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
    
    if (preg_match('/no_fullcode : (\d+)/', $log, $R) && $R[1] != 0) {
        $fullcode[] = $file." ({$R[0]})";
    }
}

if ($indexed) {
    print count($indexed)." stat.log have INDEXED\n";
    print "  + ".join("\n  + ", $indexed)."\n\n";
} else {
    print "All ".count($files)." stat.log are free of INDEXED\n\n";
}

if ($next) {
    print count($next)." stat.log have NEXT\n";
    print "  + ".join("\n  + ", $next)."\n\n";
} else {
    print "All ".count($files)." stat.log are free of NEXT\n\n";
}

if ($fullcode) {
    print count($fullcode)." stat.log have no fullcode\n";
    print "  + ".join("\n  + ", $fullcode)."\n\n";
} else {
    print "All ".count($files)." stat.log are free of no_fullcode\n";
}

print "\n".count($files)." projects collecting ".number_format($tokens, 0)." tokens\n\n";

$files = glob('projects/*/');
$sqlite_md = array();
$report_md = array();
foreach($files as $file) {
    if ($file == 'projects/default/') { continue; }
    if ($file == 'projects/tests/') { continue; }
    
    if (!file_exists($file.'Premier-markdown.md')) {
        $report_md[] = $file;
    }

    if (!file_exists($file.'Premier-sqlite.sqlite')) {
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

$files = glob('human/en/*/*');
$extra_docs = array();
foreach($files as $k => $v) {
    $extra_docs[substr($v, 9, -4)] = 1;
}

$analyzers = Analyzer\Analyzer::listAnalyzers();
$missing_doc = array();
foreach($analyzers as $a) {
    unset($extra_docs[$a]);
    $o = Analyzer\Analyzer::getInstance($a, null);
    if ($o->getDescription() === '') {
        $missing_doc[] = $a;
    }
}

if ($missing_doc) {
    print count($missing_doc)." analyzer are missing their documentation\n";
    print "  + ".join("\n  + ", $missing_doc)."\n\n";
} else {
    print "All ".count($analyzers)." analyzers have their documentation\n\n";
}

if ($extra_docs) {
    print count($extra_docs)." docs are available without analyzer\n";
    print "  + ".join("\n  + ", array_keys($extra_docs))."\n\n";
} else {
    print "All ".count($analyzers)." docs have analyzers\n\n";
}

$sqlite = new Sqlite3('/Users/famille/Desktop/analyze/data/analyzers.sqlite');
$res = $sqlite->query("select folder, name from analyzers");

$in_sqlite = array();
while($row = $res->fetchArray()) {
    $in_sqlite[] = $row['folder'].'/'.$row['name'];
}

$missing_in_sqlite = array_diff($analyzers, $in_sqlite);

$id_unassigned = $sqlite->query("select id from categories where name='Unassigned'")->fetchArray();
$id_unassigned = $id_unassigned[0];

if (count($missing_in_sqlite)) {
    print count($missing_in_sqlite)." analysers missing in Sqlite. Inserting them\n";
    
    foreach($missing_in_sqlite as $analyser) {
        list($folder, $name) = explode('/', $analyser);
        
        $sqlite->query("insert into analyzers ('folder', 'name') values ('$folder', '$name')");
        $id = $sqlite->lastInsertRowID();

        $sqlite->query("insert into analyzers_categories values ('$id', '$id_unassigned')"); 
    }
}

$unassigned = $sqlite->query("select count(*) from analyzers_categories as ac join categories as c on ac.id_categories = c.id")->fetchArray(); 
if ($unassigned[0] > 0) { 
    print $unassigned[0]." analyzers are 'unassigned'. \n";
} else {
    print "All ".$unassigned[0]." analyzers are assigned. \n";
}

$unassigned2 = $sqlite->query("select count(*) from analyzers as a left join analyzers_categories as ac on ac.id_analyzer = a.id where ac.id_categories IS NULL")->fetchArray(); 
if ($unassigned2[0] > 0) { 
    print $unassigned2[0]." analysers are not linked! \n";
} else {
    print "All ".$unassigned[0]." analyzers are linked. \n\n";
}


?>