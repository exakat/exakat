<?php

include(dirname(__DIR__).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_library');

// report errorlog problems
$count = trim(shell_exec('ls -hla projects/*/log/errors.log| wc -l '));

$r = shell_exec('ls -hla projects/*/log/errors.log| grep -v 191 | grep -v 176 ');
if ($c = preg_match_all('/project(\S*)/', $r, $R)) {
    print "$c error.log are wrong\n";
    print '  + '.join("\n  + ", $R[0])."\n\n";
    print "Total of $count error.logs\n";
} else {
    print "All $count error.logs are OK\n";
}
print "\n";

$res = shell_exec('cd tests/analyzer/; php list.php -0');
preg_match('/Total : (\d+) tests/is', $res, $R);
$totalUnitTests = $R[1];
print $totalUnitTests." total analyzer tests\n";

preg_match_all('/\s(\w*)\s*(\d+)/is', $res, $R);

if (preg_match_all('/(\w+\/\w+)\s*0/is', $res, $R)) {
    print count($R[1])." total analyzer without tests\n";
    print '  + '.join("\n  + ", $R[1])."\n\n";
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
        print '  + '.join("\n  + ", array_unique($R2[1]))."\n\n";
        
        if ($R[1] != $totalUnitTests) {
            print "Not all the tests were run! Only {$R[1]} out of $totalUnitTests. Please, run php scripts/phpunit.php\n";
        } else {
            print "All tests where recently run, some are KO\n";
        }
    } elseif (preg_match('/OK \((\d+) test, (\d+) assertions\)/is', $results, $R)) {
        if ($R[1] != $totalUnitTests) {
            print "Not all the tests were run! Only {$R[1]} out of $totalUnitTests. Please, run php scripts/phpunit.php\n";
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
$loneToken = array();
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

    if (preg_match('/loneToken : (\d+)/', $log, $R) && $R[1] != 0) {
        $loneToken[] = $file." ({$R[0]})";
    }
}

if ($indexed) {
    print count($indexed)." stat.log have INDEXED\n";
    print '  + '.join("\n  + ", $indexed)."\n\n";
} else {
    print 'All '.count($files)." stat.log are free of INDEXED\n\n";
}

if ($next) {
    print count($next)." stat.log have NEXT\n";
    print '  + '.join("\n  + ", $next)."\n\n";
} else {
    print 'All '.count($files)." stat.log are free of NEXT\n\n";
}

if ($fullcode) {
    print count($fullcode)." stat.log have no fullcode\n";
    print '  + '.join("\n  + ", $fullcode)."\n\n";
} else {
    print 'All '.count($files)." stat.log are free of no_fullcode\n";
}

if ($loneToken) {
    print count($fullcode)." stat.log have reported lone tokens\n";
    print '  + '.join("\n  + ", $loneToken)."\n\n";
} else {
    print 'All '.count($files)." stat.log are free lone tokens\n";
}

print "\n".count($files).' projects collecting '.number_format($tokens, 0)." tokens\n\n";

$files = glob('human/en/*/*');
$extraDocs = array();
foreach($files as $k => $v) {
    $extraDocs[substr($v, 9, -4)] = 1;
}

$analyzers = Analyzer\Analyzer::listAnalyzers();
$missingDoc = array();
foreach($analyzers as $a) {
    unset($extraDocs[$a]);
    $o = Analyzer\Analyzer::getInstance($a, null);
    if ($o === null) { 
        print "Couldn't get an instance for '$a'\n\n";
        continue 1;
    }
    if ($o->getDescription() === '') {
        $missingDoc[] = $a.' (human/en/'.$a.'.ini)';
    }
}

if ($missingDoc) {
    print count($missingDoc)." analyzer are missing their documentation\n";
    print '  + '.join("\n  + ", $missingDoc)."\n\n";
    
    foreach($missingDoc as $document) {
        list($documentName, ) = explode(' ', $document);
        if (!file_exists('human/en/'.$documentName.'.ini')) {
            file_put_contents('human/en/'.$documentName.'.ini', "name=\"\";\ndescription=\"\";\n");
        }
    }
} else {
    print 'All '.count($analyzers)." analyzers have their documentation\n\n";
}

if ($extraDocs) {
    print count($extraDocs)." docs are available without analyzer\n";
    print '  + '.join("\n  + ", array_keys($extraDocs))."\n\n";
} else {
    print 'All '.count($analyzers)." docs have analyzers\n\n";
}

$sqlite = new Sqlite3('data/analyzers.sqlite');
$res = $sqlite->query('select folder, name from analyzers');

$inSqlite = array();
while($row = $res->fetchArray()) {
    $inSqlite[] = $row['folder'].'/'.$row['name'];
}

$missingInSqlite = array_diff($analyzers, $inSqlite);


if (count($missingInSqlite)) {
    print count($missingInSqlite)." analysers missing in Sqlite. Inserting them\n";

    $idUnassigned = $sqlite->query("SELECT id FROM categories WHERE name='Unassigned'")->fetchArray();
    $idUnassigned = $idUnassigned[0];
    
    foreach($missingInSqlite as $analyser) {
        list($folder, $name) = explode('/', $analyser);
        
        $sqlite->query("INSERT INTO analyzers ('folder', 'name') VALUES ('$folder', '$name')");
        $id = $sqlite->lastInsertRowID();

        $sqlite->query("INSERT INTO analyzers_categories VALUES ('$id', '$idUnassigned')"); 
    }
}

$missingSeverity = $sqlite->query("SELECT count(*) FROM categories c
JOIN analyzers_categories ac ON c.id = ac.id_categories
JOIN analyzers a ON ac.id_analyzer = a.id
WHERE (c.name in ('Analyze', 'Coding Conventions', 'Dead code')) AND a.severity IS NULL")->fetchArray();
$missingSeverity = $missingSeverity[0];
if ($missingSeverity == 0) {
    print "All analysers have their severity in Sqlite.\n";

} else {
    print $missingSeverity." analysers are missing severity in Sqlite.\n";

    $res = $sqlite->query("SELECT a.folder, a.name FROM categories c
JOIN analyzers_categories ac ON c.id = ac.id_categories
JOIN analyzers a ON ac.id_analyzer = a.id
WHERE (c.name in ('Analyze', 'Coding Conventions', 'Dead code', 'Security')) AND a.severity IS NULL");

    while ($row = $res->fetchArray()) {
        print '+ '.$row['folder'].'/'.$row['name']."\n";
    }
}



$total = $sqlite->query('SELECT count(*) FROM analyzers;')->fetchArray(); 
$total = $total[0];
$unassigned = $sqlite->query("SELECT group_concat(ac.id_analyzer, ',') FROM analyzers_categories AS ac JOIN categories AS c ON ac.id_categories = c.id WHERE c.name='Unassigned';")->fetchArray(); 
if ($unassigned[0] > 0) { 
    print (substr_count($unassigned[0], ',') + 1)." analyzers are 'unassigned' : {$unassigned[0]}. \n";
} else {
    print 'All '.$total." analyzers are assigned. \n";
}

$unassignedRes = $sqlite->query('SELECT * FROM analyzers AS a LEFT JOIN analyzers_categories AS ac ON ac.id_analyzer = a.id WHERE ac.id_categories IS NULL'); 
$unassigned2 = $unassignedRes->fetchArray();
if (!empty($unassigned2)) { 
    $all = array( $unassigned2['folder'].'/'.$unassigned2['name'].'('.$unassigned2['id'].')');
    while($unassigned2 = $unassignedRes->fetchArray()) {
        $all[] = $unassigned2['folder'].'/'.$unassigned2['name'].'('.$unassigned2['id'].')';
    }

    print count($all).' analyzers are not linked! ('.join(', ', $all).")\n";
} else {
    print 'All '.$total." analyzers are linked. \n\n";
}

$analyzersCount = $sqlite->query("select count(*) from categories as c join analyzers_categories as ac on c.id = ac.id_categories where c.name='Analyze'")->fetchArray(); 
print $analyzersCount[0]." analyzers \n";

// check for analyzer log 
$res = shell_exec('grep -r javax.script.ScriptException projects/*/log/analyze.*.final.log');
if ($res === null) {
    print "All analyzer.*.final.log are clean of Exceptions. \n\n";
} else {
    $lines = explode("\n", trim($res));
    print count($lines)." projects have Exceptions problems in analyzer.*.final.log\n";
    foreach($lines as $line) {
        list($file,) = explode(':', $line);
        print '  + '.$file."\n";
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