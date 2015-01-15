<?php

include(dirname(__DIR__).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_library');

$files = glob('library/Analyzer/*/*.php');

$names = array();
foreach($files as $file) {
    $name = substr(basename($filename), 0, -4);
    $names[$name] ++;
}

$db = new \SQLite3('analyzers.sqlite');

$results = $db->query('CREATE TABLE analyzers (id INTEGER PRIMARY KEY AUTOINCREMENT, 
                                               name TEXT, 
                                               dir TEXT, 
                                               shortname TEXT,
                                               dependsOn TEXT, 
                                               severity TEXT, 
                                               timeToFix TEXT, 
                                               phpversion TEXT, 
                                               phpconfiguration TEXT
                                               )');
foreach($files as $file) {
    $res = process_file($file, $names);
    print_r($res);
    
    $db->query("INSERT INTO analyzers (name, dir, dependsOn, severity, timeToFix, phpversion, phpconfiguration, shortname) VALUES ('".implode("', '", array_values($res))."')");
    
}

function process_file($filename, $names) {
    $analyzer = \Analyzer\Analyzer::getInstance($return['dir'].'/'.$return['name'], null);

    $return = array(
        'name'       => substr(basename($filename), 0, -4),
        'dir'        => basename(dirname($filename)),

    // depends     
        'dependsOn'  => implode(', ', $analyzer->dependsOn()),
    // severity
        'severity'   => $analyzer->getSeverity(),
    // time
        'timeToFix'  => $analyzer->getTimeToFix(),
    // phpversion
        'phpversion' => $analyzer->getPhpversion(),
    // configuration
        'phpconfiguration' => $analyzer->getPhpconfiguration(),
    // shortname
        'shortname' => in_array($return['name'], $names) > 1 ? 'No' : 'Yes'
    );
    
    return $return;
}

?>