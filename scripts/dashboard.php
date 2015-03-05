<?php

define('DOC_ROOT', dirname(__DIR__));

if(strpos(basename(__FILE__), 'phar') !== false){
    require_once 'phar://exakat.phar/library/Autoload.php';
    spl_autoload_register('Autoload::autoload_library');
} else {
    include_once DOC_ROOT.'/library/Autoload.php';
    spl_autoload_register('Autoload::autoload_library');
}

$files = glob(''.DOC_ROOT.'/human/en/*'.'/*');
$extraDocs = array();
foreach($files as $k => $v) {
    $extraDocs[substr($v, 40, -4)] = 1;
}

$analyzers = Analyzer\Analyzer::listAnalyzers();
$missingDoc = array();
foreach($analyzers as $a) {
    $a = str_replace('\\', '/', $a);
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
        if (!file_exists(DOC_ROOT.'/human/en/'.$documentName.'.ini')) {
            file_put_contents(DOC_ROOT.'/human/en/'.$documentName.'.ini', "name=\"\";\ndescription=\"\";\n");
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

$sqlite = new Sqlite3(DOC_ROOT.'/data/analyzers.sqlite');
$res = $sqlite->query('SELECT folder, name FROM analyzers');

$inSqlite = array();
while($row = $res->fetchArray()) {
    $inSqlite[] = $row['folder'].'\\'.$row['name'];
}

$missingInSqlite = array_diff($analyzers, $inSqlite);

if (count($missingInSqlite)) {
    echo count($missingInSqlite)." analysers missing in Sqlite. Inserting them\n";

    $idUnassigned = $sqlite->query("SELECT id FROM categories WHERE name='Unassigned'")->fetchArray();
    $idUnassigned = $idUnassigned[0];
    
    foreach($missingInSqlite as $analyser) {
        list($folder, $name) = explode('\\', $analyser);
        
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

?>