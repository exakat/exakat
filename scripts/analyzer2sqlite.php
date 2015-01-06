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
    $return = array();

    $return['name'] = substr(basename($filename), 0, -4);
    $return['dir']  = basename(dirname($filename));
    
    $analyzer = \Analyzer\Analyzer::getInstance($return['dir'].'/'.$return['name'], null);

    // depends 
    $return['dependsOn']  = implode(', ', $analyzer->dependsOn());
    
    // severity
    $return['severity']  = $analyzer->getSeverity();

    // time
    $return['timeToFix']  = $analyzer->getTimeToFix();

    // phpversion
    $return['phpversion']  = $analyzer->getPhpversion();

    // configuration
    $return['phpconfiguration']  = $analyzer->getPhpconfiguration();

    // shortname
    $return['shortname']  = in_array($return['name'], $names) > 1 ? 'No' : 'Yes';
    
    return $return;
}

?>