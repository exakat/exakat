<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/


include(dirname(__DIR__).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_library');

// report errorlog problems
$count = trim(shell_exec('ls -hla projects/*/log/errors.log| wc -l '));

$r = shell_exec('ls -hla projects/*/log/errors.log| grep -v 191 | grep -v 176 ');
if ($c = preg_match_all('/project(\S*)/', $r, $R)) {
    echo "$c error.log are wrong\n", 
          '  + ', implode("\n  + ", $R[0]), "\n\n",
          "Total of ", $count, " error.logs\n";
} else {
    echo 'All ', $count, " error.logs are OK\n";
}
echo "\n";

$res = shell_exec('cd tests/analyzer/; php list.php -0');
preg_match('/Total : (\d+) tests/is', $res, $R);
$totalUnitTests = $R[1];
echo $totalUnitTests, " total analyzer tests\n";

preg_match_all('/\s(\w*)\s*(\d+)/is', $res, $R);

if (preg_match_all('/(\w+\/\w+)\s*0/is', $res, $R)) {
    echo count($R[1]), " total analyzer without tests\n",
          '  + '.implode("\n  + ", $R[1])."\n\n";
} else {
    echo "All analyzers have tests\n";
}

if (!file_exists('tests/analyzer/phpunit.txt')) {
    echo "No recent unit test on Analyzers! Please, run php scripts/phpunit.php\n";
} elseif (time() - filemtime('tests/analyzer/phpunit.txt') > 86400) {
    echo "Phpunit test is more than a day! Please, run php scripts/phpunit.php\n";
} else {
    $results = file_get_contents('tests/analyzer/phpunit.txt');

    if (preg_match('/Tests: (\d+), Assertions: (\d+), Failures: (\d+), Skipped: (\d+)\./is', $results, $R)) {
        preg_match_all('/\d+\) Test\\\\(\w+)::/is', $results, $R2);
        echo "There were {$R[1]} failures in ", count(array_count_values($R2[1])), " tests! Check the tests! \n",
              '  + ', implode("\n  + ", array_keys(array_count_values(($R2[1])))), "\n\n";
        
        if ($R[1] != $totalUnitTests) {
            echo "Not all the tests were run! Only {$R[1]} out of $totalUnitTests. Please, run php scripts/phpunit.php\n";
        } else {
            echo "All tests where recently run, some are KO\n";
        }
    } elseif (preg_match('/OK \((\d+) test, (\d+) assertions\)/is', $results, $R)) {
        if ($R[1] != $totalUnitTests) {
            echo "Not all the tests were run! Only {$R[1]} out of $totalUnitTests. Please, run php scripts/phpunit.php\n";
        } else {
            echo "All tests where recently run and OK\n";
        }
    } else {
        echo "Nothing found in the unit tests!\n";
    }
}
echo "\n";

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
    echo count($indexed)." stat.log have INDEXED\n",
         '  + '.implode("\n  + ", $indexed)."\n\n";
} else {
    echo 'All '.count($files)." stat.log are free of INDEXED\n\n";
}

if ($next) {
    echo count($next)." stat.log have NEXT\n",
         '  + '.implode("\n  + ", $next)."\n\n";
} else {
    echo 'All '.count($files)." stat.log are free of NEXT\n\n";
}

if ($fullcode) {
    echo count($fullcode)." stat.log have no fullcode\n".
          '  + '.implode("\n  + ", $fullcode)."\n\n";
} else {
    echo 'All '.count($files)." stat.log are free of no_fullcode\n";
}

if ($loneToken) {
    echo count($fullcode)." stat.log have reported lone tokens\n",
         '  + '.implode("\n  + ", $loneToken)."\n\n";
} else {
    echo 'All '.count($files)." stat.log are free lone tokens\n";
}

echo "\n".count($files).' projects collecting '.number_format($tokens, 0)." tokens\n\n";

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
        echo "Couldn't get an instance for '$a'\n\n";
        continue 1;
    }
    if ($o->getDescription()->getDescription() === '') {
        $missingDoc[] = $a.' (human/en/'.$a.'.ini)';
    }
}

if ($missingDoc) {
    echo count($missingDoc)." analyzer are missing their documentation\n",
         '  + '.implode("\n  + ", $missingDoc)."\n\n";
    
    foreach($missingDoc as $document) {
        list($documentName, ) = explode(' ', $document);
        if (!file_exists('human/en/'.$documentName.'.ini')) {
            file_put_contents('human/en/'.$documentName.'.ini', "name=\"\";\ndescription=\"\";\n");
        }
    }
} else {
    echo 'All '.count($analyzers)." analyzers have their documentation\n\n";
}

if ($extraDocs) {
    echo count($extraDocs)." docs are available without analyzer\n",
         '  + '.implode("\n  + ", array_keys($extraDocs))."\n\n";
} else {
    echo 'All '.count($analyzers)." docs have analyzers\n\n";
}

$sqlite = new Sqlite3('data/analyzers.sqlite');
$res = $sqlite->query('SELECT folder, name FROM analyzers');

$inSqlite = array();
while($row = $res->fetchArray()) {
    $inSqlite[] = $row['folder'].'/'.$row['name'];
}

$missingInSqlite = array_diff($analyzers, $inSqlite);


if (count($missingInSqlite)) {
    echo count($missingInSqlite)." analysers missing in Sqlite. Inserting them\n";

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
    echo "All analysers have their severity in Sqlite.\n";

} else {
    echo $missingSeverity." analysers are missing severity in Sqlite.\n";

    $res = $sqlite->query("SELECT a.folder, a.name FROM categories c
JOIN analyzers_categories ac ON c.id = ac.id_categories
JOIN analyzers a ON ac.id_analyzer = a.id
WHERE (c.name in ('Analyze', 'Coding Conventions', 'Dead code', 'Security')) AND a.severity IS NULL");

    while ($row = $res->fetchArray()) {
        echo '+ '.$row['folder'].'/'.$row['name']."\n";
    }
}



$total = $sqlite->query('SELECT count(*) FROM analyzers;')->fetchArray(); 
$total = $total[0];
$unassigned = $sqlite->query("SELECT group_concat(ac.id_analyzer, ',') FROM analyzers_categories AS ac JOIN categories AS c ON ac.id_categories = c.id WHERE c.name='Unassigned';")->fetchArray(); 
if ($unassigned[0] > 0) { 
    echo (substr_count($unassigned[0], ',') + 1)." analyzers are 'unassigned' : {$unassigned[0]}. \n";
} else {
    echo 'All '.$total." analyzers are assigned. \n";
}

$unassignedRes = $sqlite->query('SELECT * FROM analyzers AS a LEFT JOIN analyzers_categories AS ac ON ac.id_analyzer = a.id WHERE ac.id_categories IS NULL'); 
$unassigned2 = $unassignedRes->fetchArray();
if (!empty($unassigned2)) { 
    $all = array( $unassigned2['folder'].'/'.$unassigned2['name'].'('.$unassigned2['id'].')');
    while($unassigned2 = $unassignedRes->fetchArray()) {
        $all[] = $unassigned2['folder'].'/'.$unassigned2['name'].'('.$unassigned2['id'].')';
    }

    echo count($all).' analyzers are not linked! ('.implode(', ', $all).")\n";
} else {
    echo 'All '.$total." analyzers are linked. \n\n";
}

$analyzersCount = $sqlite->query("SELECT count(*) FROM categories AS c JOIN analyzers_categories AS ac ON c.id = ac.id_categories WHERE c.name='Analyze'")->fetchArray(); 
echo $analyzersCount[0]." analyzers \n";

// check for analyzer log 
$res = shell_exec('grep -r javax.script.ScriptException projects/*/log/analyze.*.final.log');
if ($res === null) {
    echo "All analyzer.*.final.log are clean of Exceptions. \n\n";
} else {
    $lines = explode("\n", trim($res));
    echo count($lines)." projects have Exceptions problems in analyzer.*.final.log\n";
    foreach($lines as $line) {
        list($file,) = explode(':', $line);
        echo '  + '.$file."\n";
    }
    
    echo "\n\n";
}

// check for errors in the analyze logs
$shell = shell_exec('grep Exception projects/*/log/analyze.* | grep "Exception : "');
$rows = explode("\n", trim($shell));

if (count($rows) > 0) {
    echo count($rows)." errors in the analyzers\n";
    foreach($rows as $row) {
        echo '+ ', $row, "\n";
    }
} else {
    echo "All project have clean analyzers\n";
}


?>