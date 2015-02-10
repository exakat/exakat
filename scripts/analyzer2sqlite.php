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