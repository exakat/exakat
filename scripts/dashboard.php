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
$lone_token = array();
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

    if (preg_match('/lone_token : (\d+)/', $log, $R) && $R[1] != 0) {
        $lone_token[] = $file." ({$R[0]})";
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

if ($lone_token) {
    print count($fullcode)." stat.log have reported lone tokens\n";
    print "  + ".join("\n  + ", $lone_token)."\n\n";
} else {
    print "All ".count($files)." stat.log are free lone tokens\n";
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
    if (is_null($o)) { 
        print "Couldn't get an instance for '$a'\n\n";
        continue 1;
    }
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

$sqlite = new Sqlite3('data/analyzers.sqlite');
$res = $sqlite->query("select folder, name from analyzers");

$in_sqlite = array();
while($row = $res->fetchArray()) {
    $in_sqlite[] = $row['folder'].'/'.$row['name'];
}

$missing_in_sqlite = array_diff($analyzers, $in_sqlite);


if (count($missing_in_sqlite)) {
    print count($missing_in_sqlite)." analysers missing in Sqlite. Inserting them\n";

    $id_unassigned = $sqlite->query("SELECT id FROM categories WHERE name='Unassigned'")->fetchArray();
    $id_unassigned = $id_unassigned[0];
    
    foreach($missing_in_sqlite as $analyser) {
        list($folder, $name) = explode('/', $analyser);
        
        $sqlite->query("INSERT INTO analyzers ('folder', 'name') VALUES ('$folder', '$name')");
        $id = $sqlite->lastInsertRowID();

        $sqlite->query("INSERT INTO analyzers_categories VALUES ('$id', '$id_unassigned')"); 
    }
}

$missing_severity = $sqlite->query("SELECT count(*) FROM categories c
JOIN analyzers_categories ac ON c.id = ac.id_categories
JOIN analyzers a ON ac.id_analyzer = a.id
WHERE (c.name in ('Analyze', 'Coding Conventions', 'Dead code')) AND a.severity IS NULL")->fetchArray()[0];
if ($missing_severity == 0) {
    print "All analysers are missing severity in Sqlite.\n";

} else {
    print $missing_severity." analysers are missing severity in Sqlite.\n";

    $res = $sqlite->query("SELECT a.folder, a.name FROM categories c
JOIN analyzers_categories ac ON c.id = ac.id_categories
JOIN analyzers a ON ac.id_analyzer = a.id
WHERE (c.name in ('Analyze', 'Coding Conventions', 'Dead code')) AND a.severity IS NULL");

    while ($row = $res->fetchArray()) {
        print '+ '.$row['folder'].'/'.$row['name']."\n";
    }

//    print_r($missing_severity);
}



$total = $sqlite->query("SELECT count(*) FROM analyzers;")->fetchArray(); 
$total = $total[0];
$unassigned = $sqlite->query("select count(*) from analyzers_categories as ac join categories as c on ac.id_categories = c.id WHERE c.name='Unassigned';")->fetchArray(); 
if ($unassigned[0] > 0) { 
    print $unassigned[0]." analyzers are 'unassigned'. \n";
} else {
    print "All ".$total." analyzers are assigned. \n";
}

$unassigned_res = $sqlite->query("select * from analyzers as a left join analyzers_categories as ac on ac.id_analyzer = a.id where ac.id_categories IS NULL"); 
$unassigned2 = $unassigned_res->fetchArray();
if (!empty($unassigned2)) { 
    $all = array( $unassigned2['folder'].'/'.$unassigned2['name'].'('.$unassigned2['id'].')');
    while($unassigned2 = $unassigned_res->fetchArray()) {
        $all[] = $unassigned2['folder'].'/'.$unassigned2['name'].'('.$unassigned2['id'].')';
    }

    print count($all)." analyzers are not linked! (".join(', ', $all).")\n";
} else {
    print "All ".$total." analyzers are linked. \n\n";
}

$analyzers_count = $sqlite->query("select count(*) from categories as c join analyzers_categories as ac on c.id = ac.id_categories where c.name='Analyze'")->fetchArray(); 
print $analyzers_count[0]." analyzers \n";

// check for analyzer log 
$res = shell_exec('grep -r javax.script.ScriptException projects/*/log/analyze.*.final.log');
if ($res === null) {
    print "All analyzer.*.final.log are clean of Exceptions. \n\n";
} else {
    $lines = explode("\n", trim($res));
    print count($lines)." projects have Exceptions problems in analyzer.*.final.log\n";
    foreach($lines as $line) {
        list($file, $b) = explode(':', $line);
        print "  + ".$file."\n";
    }
    
    print "\n\n";
}

// check for errors in the analyze logs
$shell = shell_exec('grep Exception projects/*/log/analyze.* | grep "Exception : "');
$rows = explode("\n", trim($shell));

if (count($rows) > 0) {
    print count($rows)." errors in the analyzers\n";
    foreach($rows as $row) {
        print "+ $row\n";
    }
} else {
    print "All project have clean analyzers\n";
}


?>