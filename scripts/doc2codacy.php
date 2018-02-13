<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

include dirname(__DIR__).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_library');

$docs = new Docs('./data/analyzers.sqlite');
$list = $docs->getThemeAnalyzers('Codacy');

const DOC_ROOT = '../docker/docs';

rename(DOC_ROOT.'/tests/', '/tmp/codacy-tests/');
if (file_exists(DOC_ROOT)) {
    print "Removing ".DOC_ROOT."\n";
    rmdirRecursive(DOC_ROOT);
}
mkdir(DOC_ROOT, 0755);
mkdir(DOC_ROOT.'/description/', 0755);
rename('/tmp/codacy-tests/', DOC_ROOT.'/tests/');

$description = array();

$timetofix = array( 'Instant' => 5,
                    'Quick'   => 15,
                    'Slow'    => 30);

foreach($list as $doc) {
    $ini = parse_ini_file('./human/en/'.$doc.'.ini');
    $d = array(
        'patternId'   => $doc,
        'title'       => $ini['name'],
        'description' => $ini['name'],
        'timetofix'   => $timetofix[$docs->getTimeToFix('Exakat\\Analyzer\\'.str_replace('/', '\\', $doc))],
    );
    
    $description[] = (object) $d;
    
    $dir = dirname($doc);
    if (!file_exists(DOC_ROOT.'/description/'.$dir)) {
        print "adding ".DOC_ROOT.'/description/'.$dir."\n";
        mkdir(DOC_ROOT.'/description/'.$dir, 0755);
    }
    
    $md = preg_replace('/`(.*?)\s+<(.*?)>`_/', '[$1]($2)', $ini['description']);
    // Link replacements
    
    // lists are the same 
    
    //tables
    
    file_put_contents(DOC_ROOT.'/description/'.$doc.'.md', $md);
}

print "adding ".DOC_ROOT."/description/description.json\n";
$descriptionFile = fopen(DOC_ROOT.'/description/description.json', 'w+');
fwrite($descriptionFile, json_encode($description, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
fclose($descriptionFile);

/// patterns

$patterns = new stdclass();
$patterns->name = 'exakat';

$patterns->patterns = array();

foreach($list as $doc) {
    $pattern = new stdclass();
    $pattern->patternId  = $doc;
    $pattern->level      = 'Warning';
    $pattern->category   = 'ErrorProne';
    $pattern->parameters = array();
    
    $patterns->patterns[] = $pattern;
}
print "adding ".DOC_ROOT."/patterns.json\n";
$patternsFile = fopen(DOC_ROOT.'/patterns.json', 'w+');
fwrite($patternsFile, json_encode($patterns, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
fclose($patternsFile);

print "End\n";

?>