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

use Exakat\Analyzer\Docs;
use Exakat\Config;

include_once(dirname(__DIR__).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_library');

$docs = new Docs('./data/analyzers.sqlite');
$list = $docs->getThemeAnalyzers('Codacy');

const DOC_ROOT = '../docker-codacy/docs';

if (file_exists(DOC_ROOT)) {
    print "Removing ".DOC_ROOT."\n";
    rmdirRecursive(DOC_ROOT);
}

mkdir(DOC_ROOT, 0755);

$description = array();

foreach($list as $doc) {
    $ini = parse_ini_file('./human/en/'.$doc.'.ini');
    $d = array();
    
    $d['patternId'] = $doc;
    $d['title'] = $ini['name'];
    $d['description'] = $ini['name'];
    $d['timetofix'] = $docs->getSeverity('\\Exakat\\Analyzer\\'.str_replace('/', '\\', $doc));
    
    $description[] = (object) $d;
    
    $dir = dirname($doc);
    if (!file_exists(DOC_ROOT.'/'.$dir)) {
        print "adding ".DOC_ROOT.'/'.$dir."\n";
        mkdir(DOC_ROOT.'/'.$dir, 0755);
    }
    
    $md = preg_replace('/`(.*?)\s+<(.*?)>`_/', '[$1]($2)', $ini['description']);
    // Link replacements
    
    // lists are the same 
    
    //tables
    
    file_put_contents(DOC_ROOT.'/'.$doc.'.md', $md);
}

$descriptionFile = fopen(DOC_ROOT.'/description.json', 'w+');
fwrite($descriptionFile, json_encode($description, JSON_UNESCAPED_SLASHES));
fclose($descriptionFile);

print "End\n";
