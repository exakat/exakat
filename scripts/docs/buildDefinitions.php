<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation,either version 3 of the License,or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not,see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/

$sqlite = new \Sqlite3('data/analyzers.sqlite');

$res = $sqlite->query('SELECT COUNT(*)
                FROM categories c
                JOIN analyzers_categories ac
                    ON c.id = ac.id_categories
                JOIN analyzers a
                    ON a.id = ac.id_analyzer
                WHERE c.name = "Analyze"');
$analyzer_count = $res->fetchArray(\SQLITE3_NUM)[0];

// extensions services
/////////////////////////
$extension_list = array();
$ext = glob('./human/en/Extensions/Ext*.ini');
foreach($ext as $f) {
    $ini = parse_ini_file($f);
    
    // We take the first URL that we encounter.
    if (preg_match('/<(http:.*?)>/', $ini['description'], $r)) {
        $extension_list[] = '* `'.$ini['name'].' <'.$r[1].'>`_';
    } else {
        $extension_list[] = '* '.$ini['name'];
    }
}
$extension_list = implode("\n", $extension_list);

// library services
/////////////////////////
$library_list = array();
$json = json_decode(file_get_contents('data/externallibraries.json'));
foreach( (array) $json as $library) {
    if (empty($library->homepage)) {
        $library_list[] = '* '.$library->name;
    } else {
        $library_list[] = '* `'.$library->name.' <'.$library->homepage.'>`_';
    }
}
$library_list = implode("\n", $library_list);

// reports
/////////////////////////
$reports_list = array();
include __DIR__.'/../../library/Exakat/Reports/Reports.php' ;
$reports_list = \Exakat\Reports\Reports::$FORMATS;
$reports_list = '  * '.implode("\n  * ", $reports_list)."\n";

// themes
/////////////////////////
$themes_list = array();
$res = $sqlite->query('SELECT name FROM categories c ORDER BY name');
while($row = $res->fetchArray(\SQLITE3_NUM)) {
    $themes_list[] = '* '.$row[0];
}
$themes_list = implode("\n", $themes_list);

// themes
/////////////////////////
$external_services_list = array();
$json = json_decode(file_get_contents('data/serviceConfig.json'));
foreach( (array) $json as $name => $service) {
    $external_services_list[] = '* ['.$name.']('.$service->homepage.') - '.implode(', ', $service->file);
}
$external_services_list = implode("\n", $external_services_list);

$analyzer_introduction = generateAnalyzerList();


/// URL

$raw = explode("\n", shell_exec('grep -r \'>\`_\' '.__DIR__.'/../../docs/src/'));
$urls = array();
foreach($raw as $line) {
    preg_match_all('/(`.*?>`_)/s', $line, $r);
    $urls[] = $r[1];
}

$urls = array_merge(...$urls);

$files = glob('./human/en/*/*.ini');
foreach($files as $file) {
    $ini = parse_ini_file($file);

    if (preg_match('/(`[^`]*?>`_)/s', $ini['description'], $r)) {
        $urls[] = $r[1];
    }
}

$urls = array_keys(array_count_values($urls));

uasort($urls, function($a, $b) { 
    preg_match('/`(.+) </', $a, $aa);
    preg_match('/`(.+) </', $b, $bb);
    if (empty($aa[1])) {
        print "Empty link : $a\n";
    } elseif (empty($bb[1])) {
        print "Empty link : $b\n";
    } elseif ($aa[1] == $bb[1]) {
        print "Double link : $a / $b\n";
    }
    
    return strtolower($a) <=> strtolower($b); 
});

$url_list = "* ".implode("\n* ", $urls)."\n";

/// URL
$exakat_site = 'https://www.exakat.io/';

$php = file_get_contents('./library/Exakat/Exakat.php');
//    const VERSION = '1.0.3';
preg_match('/const VERSION = \'([0-9\.]+)\';/is', $php, $r);
$exakat_version = $r[1];
//    const BUILD = 661;
preg_match('/const BUILD = ([0-9]+);/is', $php, $r);
$exakat_build = $r[1];

$exakat_date = date('r', filemtime('./library/Exakat/Exakat.php'));

// More to come,and automate collection too
$attributes = array('ANALYZERS_COUNT'        => $analyzer_count,
                    'EXTENSION_LIST'         => $extension_list,
                    'LIBRARY_LIST'           => $library_list,
                    'ANALYZER_INTRODUCTION'  => $analyzer_introduction,
                    'EXTERNAL_SERVICES_LIST' => $external_services_list,
                    'REPORTS_LIST'           => $reports_list,
                    'THEMES_LIST'            => $themes_list,
                    'URL_LIST'               => $url_list,
                    'EXAKAT_VERSION'         => $exakat_version,
                    'EXAKAT_BUILD'           => $exakat_build,
                    'EXAKAT_SITE'            => $exakat_site,
                    'EXAKAT_DATE'            => $exakat_date,
                    );

shell_exec('rm docs/*.rst');
shell_exec('cp docs/src/*.rst docs/');

global $applications, $issues_examples, $parameter_list;
$applications = array();
$issues_examples = array();
$parameter_list = array();

build_reports();

shell_exec('cp docs/src/images/*.png docs/images/');

$recipes = array('Analyze',
                 'CompatibilityPHP73',
                 'CompatibilityPHP72',
                 'CompatibilityPHP71',
                 'CompatibilityPHP70',
                 'CompatibilityPHP56',
                 'CompatibilityPHP55',
                 'CompatibilityPHP54',
                 'CompatibilityPHP53',
                 'Security',
                 'Performances',
                 'Dead code',
                 'Coding Conventions',
                 'Suggestions',
                 'Wordpress',
                 'Slim',
                 'ZendFramework',
                 'Cakephp',
                 );

$text = '';
$recipesList = '"'.implode('","',$recipes).'"';
$glossary = array();
$ini = parse_ini_file('./data/php_functions.ini');
foreach($ini['functions'] as &$f) {
    $f .= '()';
}
unset($f);

$entries = array_flip($ini['functions']);
foreach($entries as $f => &$link) {
    $link = 'http://www.php.net/'.substr($f, 0, -2);
}
unset($link);

$extras = array( 'switch()'                       => 'http://php.net/manual/en/control-structures.switch.php',
                 'for()'                          => 'http://php.net/manual/en/control-structures.for.php',
                 'foreach()'                      => 'http://php.net/manual/en/control-structures.foreach.php',
                 'while()'                        => 'http://php.net/manual/en/control-structures.while.php',
                 'do..while()'                    => 'http://php.net/manual/en/control-structures.do.while.php',
   
                 'die'                            => 'http://www.php.net/die',
                 'exit'                           => 'http://www.php.net/exit',
                 'isset'                          => 'http://www.php.net/isset',
                 'break'                          => 'http://php.net/manual/en/control-structures.break.php',
                 'continue'                       => 'http://php.net/manual/en/control-structures.continue.php',
                 'instanceof'                     => 'http://php.net/manual/en/language.operators.type.php',
                 'insteadof'                      => 'http://php.net/manual/en/language.oop5.traits.php',
                     
                 '**'                             => 'http://php.net/manual/en/language.operators.arithmetic.php',
                 '$_GET'                          => 'http://php.net/manual/en/reserved.variables.get.php',
                 '$_POST'                         => 'http://php.net/manual/en/reserved.variables.post.php',
                 '$HTTP_RAW_POST_DATA'            => 'http://php.net/manual/en/reserved.variables.httprawpostdata.php',
                 '$this'                          => 'http://php.net/manual/en/language.oop5.basic.php',
                  
                 '__construct'                  => 'http://php.net/manual/en/language.oop5.decon.php',
                 '__destruct'                   => 'http://php.net/manual/en/language.oop5.decon.php',
                  
                 '__call'                       => 'http://php.net/manual/en/language.oop5.magic.php',
                 '__callStatic'                 => 'http://php.net/manual/en/language.oop5.magic.php',
                 '__get'                        => 'http://php.net/manual/en/language.oop5.magic.php',
                 '__set'                        => 'http://php.net/manual/en/language.oop5.magic.php',
                 '__isset'                      => 'http://php.net/manual/en/language.oop5.magic.php',
                 '__unset'                      => 'http://php.net/manual/en/language.oop5.magic.php',
                 '__sleep'                      => 'http://php.net/manual/en/language.oop5.magic.php',
                 '__wakeup'                     => 'http://php.net/manual/en/language.oop5.magic.php',
                 '__toString'                   => 'http://php.net/manual/en/language.oop5.magic.php',
                 '__invoke'                     => 'http://php.net/manual/en/language.oop5.magic.php',
                 '__set_state'                  => 'http://php.net/manual/en/language.oop5.magic.php',
                 '__clone'                      => 'http://php.net/manual/en/language.oop5.magic.php',
                 '__debugInfo'                  => 'http://php.net/manual/en/language.oop5.magic.php',
                 
                 'ArrayAccess'                    => 'http://php.net/manual/en/class.arrayaccess.php',
                 'Throwable'                      => 'http://php.net/manual/fr/class.throwable.php',
                 'Closure'                        => 'http://php.net/manual/fr/class.closure.php',
                 'Traversable'                    => 'http://php.net/manual/fr/class.traversable.php',
                 'ParseError'                     => 'http://php.net/manual/fr/class.parseerror.php',
                 
                 '__FILE__'                   => 'http://php.net/manual/en/language.constants.predefined.php',
                 '__DIR__'                    => 'http://php.net/manual/en/language.constants.predefined.php',
                 '__LINE__'                   => 'http://php.net/manual/en/language.constants.predefined.php',
                 '__CLASS__'                  => 'http://php.net/manual/en/language.constants.predefined.php',
                 '__METHOD__'                 => 'http://php.net/manual/en/language.constants.predefined.php',
                 '__NAMESPACE__'              => 'http://php.net/manual/en/language.constants.predefined.php',
                 '__TRAIT__'                  => 'http://php.net/manual/en/language.constants.predefined.php',
                 '__FUNCTION__'               => 'http://php.net/manual/en/language.constants.predefined.php',
);
$entries = array_merge($entries, $extras);

$query = 'SELECT a.folder || "/" || a.name AS analyzer,GROUP_CONCAT(c.name) analyzers  
                FROM categories c
                JOIN analyzers_categories ac
                    ON c.id = ac.id_categories
                JOIN analyzers a
                    ON a.id = ac.id_analyzer
                WHERE c.name IN ('.$recipesList.')
                GROUP BY a.name';
$res = $sqlite->query($query);
$a2themes = array();
while($row = $res->fetchArray(SQLITE3_ASSOC)) {
   $a2themes[$row['analyzer']] = explode(',',$row['analyzers']);
}

$query = 'SELECT c.name,GROUP_CONCAT(a.folder || "/" || a.name) analyzers  
                FROM categories c
                JOIN analyzers_categories ac
                    ON c.id = ac.id_categories
                JOIN analyzers a
                    ON a.id = ac.id_analyzer
                WHERE c.name IN ('.$recipesList.')
                GROUP BY c.name';

$res = $sqlite->query($query);
$analyzers = array();
$deja = array();
while($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $liste = explode(',',$row['analyzers']);

    foreach($liste as &$a) {
        if (isset($deja[$a])) { continue; }
        $deja[$a] = 1;
        list($desc, $name) = build_analyzer_doc($a, $a2themes);
        $a = rst_link($name);
        $analyzers[$name] = $desc;
    }
    unset($a);

    sort($liste);
    $text .= rst_level($row['name'],4)."\nTotal : ".count($liste)." analysis\n\n* ".implode("\n* ",$liste)."\n\n";
}

$rules = '';
ksort($analyzers);
foreach($analyzers as $title => $desc) {
    $rules .= rst_level($title,3).PHP_EOL.PHP_EOL.$desc.PHP_EOL;
}


$attributes['ISSUES_EXAMPLES'] = join('', $issues_examples);

//$attributes['PARAMETER_LIST'] = $parameter_list;

$attributes['APPLICATIONS'] = makeApplicationsLink(array_keys($applications));

$files = glob('docs/*.rst');
foreach($files as $file) {
    $rst = file_get_contents($file);
    
    $rst = str_replace(array_map(function ($x) { return '{{'.$x.'}}'; }, array_keys($attributes)), array_values($attributes), $rst);
    if (preg_match_all('/{{(.*?)}}/',$rst,$r)) {
        print "There are ".count($r[1])." missed attributes in \"".basename($file)."\" : ".implode(",",$r[1])."\n\n";
    }
    
    file_put_contents(str_replace('/src/','/',$file),$rst);
}

$rst = file_get_contents('docs/src/Recipes.rst');
$date = date('r');
$hash = shell_exec('git rev-parse HEAD');
$rst = preg_replace('/.. comment: Recipes details(.*)$/is',".. comment: Recipes details\n.. comment: Generation date : $date\n.. comment: Generation hash : $hash\n\n$text",$rst);
print file_put_contents('docs/Recipes.rst',$rst)." octets written for Recipes\n";

$rst = file_get_contents('docs/src/Rules.rst');
$rst = preg_replace('/.. comment: Rules details(.*)$/is',".. comment: Rules details\n.. comment: Generation date : $date\n.. comment: Generation hash : $hash\n\n$rules",$rst);
print file_put_contents('docs/Rules.rst',$rst)." octets written for Rules\n";

$glossaryRst = <<<GLOSSARY
.. Glossary:

Glossary
============

GLOSSARY;
ksort($glossary);

$found = 0;
foreach($glossary as $items) {
    $found += count(array_keys($items));
}
print "$found found\n";
print count($entries)." defined\n";

foreach($entries as $name => $url) {
    $letter = strtoupper(trim($name,'\\`'))[0];
}

foreach($glossary as $letter => $items) {
    $glossaryRst .= "+ `$letter`\n";
    ksort($items);
    foreach($items as $key => $urls) {
        ksort($urls);
        $glossaryRst .= "    + `".stripslashes($key)."`\n
      + ".implode("\n      + ",array_keys($urls))."\n\n";
    }
    $glossaryRst .= "\n";
}
$glossaryRst .= "\n";
print file_put_contents('docs/Glossary.rst',$glossaryRst)." octets written for Rules\n";

function rst_anchor($name) {
    return str_replace(array(' ','_',':'),array('-','\\_','\\:'),strtolower($name));
}

function rst_anchor_def($name) {
    return '.. _'.rst_anchor($name).":\n\n";
}

function rst_escape($string) {
    $r = preg_replace_callback('/<\?php(.*?)\?>/is',function ($r) {
        $r[0] = preg_replace('/`([^ ]+?) .*?`_/','$1',$r[0]);
        $rst = ".. code-block:: php\n\n   ".str_replace("\n","\n   ",$r[0])."\n";
        return $rst;
    },$string);

    $r = preg_replace_callback('/\s*<\?literal(.*?)\?>/is',function ($r) {
        $rst = "::\n\n   ".str_replace("\n","\n   ",$r[1])."\n";
        return $rst;
    },$r);

    $r = str_replace(array('**='),array('\\*\\*\\='),$r);
    
    return $r;
}

function rst_link($title) {
    if (strpos($title,' ') !== false) {
        $escapeTitle = rst_anchor($title);
        return ':ref:`'.rst_escape($title).' <'.$escapeTitle.'>`';
    } else {
        return ':ref:`'.rst_escape($title).'`';
    }
}

function rst_level($title,$level = 1) {
    $levels = array(1 => '=',2 => '-',3 => '#',4 => '+');
    $escapeTitle = rst_escape($title);
    return rst_anchor_def($title).$escapeTitle."\n".str_repeat($levels[$level],strlen($escapeTitle))."\n";
}

function glossary($title,$description) {
    global $glossary,$entries;

    $alt = implode('|',array_keys($entries));
    $alt = str_replace(array('*','(',')'),array('\\*','\(','\)'),$alt);
    
    $cbGlossary = function ($r) use ($title) {
        global $glossary,$entries;
        
        $letter = strtoupper($r[2]{0});
        $glossary[$letter][$r[2]][':ref:`'.$title.' <'.rst_anchor($title).'>`'] = 1;
        
        if (isset($entries[$r[2]])) {
            $url = $entries[$r[2]];
            return $r[1].'`\''.$r[2].$r[3].' <'.$url.'>`_';
        } else {
            return $r[0];
        }

    };
    
    $description = preg_replace_callback('/([^a-zA-Z_`])('.$alt.')(\(?\)?)(?=[^a-zA-Z_=])/is', $cbGlossary, ' '.$description);

    return $description;
}

function generateAnalyzerList() {
    $files = glob('./human/en/*/*.ini');
    
    $sqlite = new \Sqlite3('data/analyzers.sqlite');
    
    $versions = array();
    foreach($files as $file) {
        $folder = basename(dirname($file));
        if ($folder === 'Reports') { continue; }
        $analyzer = substr(basename($file), 0, -4);
        $name = $folder.'/'.$analyzer;
        
        $res = $sqlite->query(<<<SQL
SELECT GROUP_CONCAT(c.name, ', ') AS categories FROM analyzers a
    JOIN analyzers_categories ac
        ON ac.id_analyzer = a.id
    JOIN categories c
        ON c.id = ac.id_categories
    WHERE
        a.folder = "$folder" AND
        a.name   = "$analyzer" AND
        c.name   != 'All'
SQL
);
        $row = $res->fetchArray(\SQLITE3_ASSOC);
        
        $ini = parse_ini_file($file);
        if (empty($ini['exakatSince'])) {
            print "No exakatSince in ".$file."\n";
            continue;
        }
        if (isset($versions[$ini['exakatSince']])) {
            $versions[$ini['exakatSince']][] = $ini['name'].' ('.$name.' ; '.$row['categories'].')';
        } else {
            $versions[$ini['exakatSince']] = array($ini['name'].' ('.$name.')');
        }
    }
    uksort($versions, function ($a, $b) { return version_compare($b, $a); });
    
    $list = "\n";
    foreach($versions as $version => $analyzers) {
        $list .= '* '.$version."\n\n";
        sort($analyzers);
        $list .= '  * '.implode("\n  * ", $analyzers)."\n\n";
    }
    $list .= "\n";

    return $list;
}

function build_reports() {
    $file = file_get_contents('./docs/Reports.rst');
    
    $list = glob('./human/en/Reports/*.ini');
    $reportList = array();
    $reportSection = array();
    foreach($list as $reportFile) {
        $reportIni = parse_ini_file($reportFile);
        
        $reportList[] = '`'.$reportIni['name'].'`_';

        $section = $reportIni['name']."\n".str_repeat('-', strlen($reportIni['name']))."\n\n";
        $section .= $reportIni['mission']."\n\n".$reportIni['description']."\n\n";

        foreach($reportIni['examples'] as $id => $example) {
            if (preg_match('/\.png$/', $example)) {
                $section .= ".. image:: images/$example
    :alt: Example of a $reportIni[name] report ($id)

";
            } elseif (preg_match('/\.txt$/', $example)) {
                $exampleTxt = file_get_contents('./docs/src/images/'.$example);
                $exampleTxt = '    '.str_replace("\n", "\n    ", $exampleTxt);
                $section .= "\n::

$exampleTxt

";
            }
        }
        
        if (!empty($reportIni['depends'][0])) {
            if (count($reportIni['depends']) === 1) {
                $section .= $reportIni['name']. ' includes the report from another other report : '.join(', ', $reportIni['depends']).".\n\n";
            } else {
                sort($reportIni['depends']);
                $section .= $reportIni['name']. ' includes the report from '.count($reportIni['depends']).' other reports : '.join(', ', $reportIni['depends']).".\n\n";
            }
        }

        $section .= $reportIni['name']. " is a $reportIni[type] report format.\n\n";

        $reportSection[] = $section;
    }
    
    $reportList = '* '.join("\n* ", $reportList).PHP_EOL;
    $reportSection = join('', $reportSection).PHP_EOL;
    
    $file = str_replace('REPORT_LIST', $reportList, $file);
    $file = str_replace('REPORT_DETAILS', $reportSection, $file);
    
    file_put_contents('./docs/Reports.rst', $file);
}

function build_analyzer_doc($a, $a2themes) {
    global $applications, $issues_examples, $parameter_list;
    
        $name = $a;
        $ini = parse_ini_file("human/en/$a.ini", true);
        $commandLine = $a;

        $desc = glossary($ini['name'],$ini['description']);
        $desc = trim(rst_escape($desc));

        if (!empty($ini['clearphp'])) {
            $clearPHP = "`$ini[clearphp] <https://github.com/dseguy/clearPHP/tree/master/rules/$ini[clearphp].md>`__";
        } else {
            $clearPHP = '';
        }

        if (isset($a2themes[$name])) {
            $c = array_map('rst_link',$a2themes[$name]);
            $recipes = implode(', ',$c);
        } else {
            $recipes = 'none';
        }
        
        $examples = array();
        $issues_examples_section_list = array();
        for($i = 0; $i < 10; $i++) {

            if (isset($ini['example'.$i])) {
                $issues_examples_section = '';
                $label = rst_anchor($ini['example'.$i]['project'].'-'.str_replace('/', '-', strtolower($a)));
                
                $examples[] = ':ref:`'.$label.'`';
                $code = "    ".str_replace("\n", "\n    ", trim($ini['example'.$i]['code']));
                $section = $ini['example'.$i]['project']."\n".str_repeat('^', strlen($ini['example'.$i]['project']));
                $explain = $ini['example'.$i]['explain'];
                $file = $ini['example'.$i]['file'];
                $line = $ini['example'.$i]['line'];
                $analyzer_anchor = rst_anchor($ini['name']);

                if (empty($issues_examples_section)){
                    $issues_examples_section = $ini['name']."\n".str_repeat('=', strlen($ini['name']))."\n";
                }

                $issues_examples_section .= <<<SPHINX

.. _$label:

$section

:ref:`$analyzer_anchor`, in $file:$line. 

$explain

.. code-block:: php

$code


SPHINX;
                $applications[$ini['example'.$i]['project']] = 1;
                $issues_examples_section_list[] = $issues_examples_section;
            }
        }
        
        $issues_examples_section = join(PHP_EOL.'--------'.PHP_EOL.PHP_EOL, $issues_examples_section_list);

        $parameters = array();
        for($i = 0; $i < 10; $i++) {
            if (isset($ini['parameter'.$i])) {
                $parameters[] = [$ini['parameter'.$i]['name'],
                                 $ini['parameter'.$i]['default'],
                                 $ini['parameter'.$i]['type'],
                                 $ini['parameter'.$i]['description'],
                                 ];
            }
        }
        
        if (!empty($parameters)) {
            array_unshift($parameters, ["Name", "Default", "Type", "Description"]);
            $desc .= PHP_EOL.PHP_EOL.makeTable($parameters).PHP_EOL;
        }
        
        if (!empty($issues_examples_section)){
            $issues_examples[] = $issues_examples_section;
        }

        $info = array( array('Short name', $commandLine),
                       array('Themes', $recipes),
                                );
        if (!empty($clearPHP)) {
            $info[] = array('ClearPHP', $clearPHP);
        }
        if (!empty($examples)) {
            $info[] = array('Examples', join(', ', $examples));
        }

        $table = makeTable($info);
                 
        $desc .= PHP_EOL.PHP_EOL.$table.PHP_EOL.PHP_EOL;

        return array($desc, $ini['name']);
}

function makeTable($array) {
    $sizes = array();
    foreach(array_keys($array[0]) as $col) {
        $values = array_column($array, $col);
        $strlens = array_map('strlen', $values);
        $sizes[] = max($strlens);
    }
    
    $separator = '+'.join('+', array_map(function($x) { return str_pad('', $x + 2, '-'); }, $sizes)).'+'.PHP_EOL;

    $return = $separator;
    foreach($array as $row) {
        $str = '|';
        foreach($row as $col => $value) {
            $str .= ' '.str_pad($value, $sizes[$col], ' ').' |';
        }
        
        $return .= $str.PHP_EOL.$separator;
    }

    return $return;
}

function makeApplicationsLink($names) {
    include __DIR__.'/applications.php';

    $names = array_map(function($x) use ($applications) { if (isset($applications[$x])) { $x = "`$x <".$applications[$x]['url'].">`_";} else { print "Missing url for $x\n"; } return "* $x\n"; }, $names);
    return join('', $names);
}

?>