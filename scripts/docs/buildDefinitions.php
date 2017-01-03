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

$extension_list = array();
$ext = glob('./human/en/Extensions/Ext*.ini');
foreach($ext as $f) {
    $ini = parse_ini_file($f);
    $extension_list[] = '* '.$ini['name'];
}
$extension_list = join("\n", $extension_list);

$library_list = array();
$json = json_decode(file_get_contents('data/externallibraries.json'));
foreach( (array) $json as $library) {
    if (!isset($library->name)) {
        print_r($library);
    }
    $library_list[] = '* ['.$library->name.']('.$library->homepage.')';
}
$library_list = join("\n", $library_list);

$external_services_list = array();
$json = json_decode(file_get_contents('data/serviceConfig.json'));
foreach( (array) $json as $name => $service) {
    $external_services_list[] = '* ['.$name.']('.$service->homepage.') - '.implode(', ', $service->file);
}
$external_services_list = join("\n", $external_services_list);


$analyzer_introduction = generateAnalyzerList();

// More to come,and automate collection too
$attributes = array('ANALYZERS_COUNT'        => $analyzer_count,
                    'EXTENSION_LIST'         => $extension_list,
                    'LIBRARY_LIST'           => $library_list,
                    'ANALYZER_INTRODUCTION'  => $analyzer_introduction,
                    'EXTERNAL_SERVICES_LIST' => $external_services_list,
                    );

shell_exec('rm docs/*.rst');

$files = glob('docs/src/*.rst');
foreach($files as $file) {
    $rst = file_get_contents($file);
    
    $rst = str_replace(array_map(function ($x) { return '{{'.$x.'}}'; },array_keys($attributes)),array_values($attributes),$rst);
    if (preg_match_all('/{{(.*?)}}/',$rst,$r)) {
        print "There are ".count($r[1])." missed attributes in \"".basename($file)."\" : ".implode(",",$r[1])."\n\n";
    }
    
    file_put_contents(str_replace('/src/','/',$file),$rst);
}

$recipes = array("Analyze",
                 "CompatibilityPHP72",
                 "CompatibilityPHP71",
                 "CompatibilityPHP70",
                 "CompatibilityPHP56",
                 "CompatibilityPHP55",
                 "CompatibilityPHP54",
                 "CompatibilityPHP53",
                 "Analyze",
                 "Security",
                 "Performances",
                 "Dead code",
                 "Coding Conventions",
                 "Wordpress",
                 );

$text = '';
$recipesList = '"'.join('","',$recipes).'"';
$glossary = array();
$entries = array('preg_replace'                   => 'http://www.php.net/preg_replace',
                 'preg_match'                     => 'http://www.php.net/preg_match',
                 'preg_replace_callback_array'    => 'http://www.php.net/preg_replace_callback_array',
                 'pow'                            => 'http://www.php.net/pow',
                 'array_unique'                   => 'http://www.php.net/array_unique',
                 'array_count_values'             => 'http://www.php.net/array_count_values',
                 'array_flip'                     => 'http://www.php.net/array_flip',
                 'array_keys'                     => 'http://www.php.net/array_keys',
                 'array_merge_recursive'          => 'http://www.php.net/array_merge_recursive',
                 'array_merge'                    => 'http://www.php.net/array_merge',
                 'array_diff'                     => 'http://www.php.net/array_diff',
                 'array_intersect'                     => 'http://www.php.net/array_intersect',
                 'array_map'                     => 'http://www.php.net/array_map',
                 'array_search'                     => 'http://www.php.net/array_search',
                 'array_udiff'                     => 'http://www.php.net/array_udiff',
                 'array_uintersect'                     => 'http://www.php.net/array_uintersect',
                 'array_unshift'                     => 'http://www.php.net/array_unshift',
                 'array_walk'                     => 'http://www.php.net/array_walk',
                 'in_array'                     => 'http://www.php.net/in_array',
                 'strstr'                     => 'http://www.php.net/strstr',
                 'isset'                     => 'http://www.php.net/isset',
                 'in_array'                     => 'http://www.php.net/in_array',

                 'strpos'                         => 'http://www.php.net/strpos',
                 'stripos'                        => 'http://www.php.net/stripos',
                 'throw'                          => 'http://www.php.net/throw',
                 'curl_share_strerror'            => 'http://www.php.net/curl_share_strerror',
                 'curl_multi_errno'               => 'http://www.php.net/curl_multi_errno',
                 'random_int'                     => 'http://www.php.net/random_int',
                 'random_bytes'                   => 'http://www.php.net/random_bytes',
                 'openssl_random_pseudo_bytes'    => 'http://www.php.net/openssl_random_pseudo_bytes',
                 'rand'                           => 'http://www.php.net/rand',
                 'srand'                          => 'http://www.php.net/srand',
                 'mt_rand'                        => 'http://www.php.net/mt_rand',
                 'mt_srand'                       => 'http://www.php.net/mt_srand',
                 'set_exception_handler'          => 'http://www.php.net/set_exception_handler',
                 'join'                           => 'http://www.php.net/join',
                 'implode'                        => 'http://www.php.net/implode',
                 'file'                           => 'http://www.php.net/file',
                 'file_get_contents'              => 'http://www.php.net/file_get_contents',
                 'file_put_contents'              => 'http://www.php.net/file_put_contents',
                 'fopen'                          => 'http://www.php.net/fopen',
                 'fclose'                         => 'http://www.php.net/fclose',
                 'time'                           => 'http://www.php.net/time',
                 'strtotime'                      => 'http://www.php.net/strtotime',
                 'array_key_exists'               => 'http://www.php.net/array_key_exists',
                 'date'                           => 'http://www.php.net/date',
                 'microtime'                      => 'http://www.php.net/microtime',
                 'sleep'                          => 'http://www.php.net/sleep',
                 'usleep'                         => 'http://www.php.net/usleep',
                 'abs'                            => 'http://www.php.net/abs',
                 'count'                          => 'http://www.php.net/count',
                 'get_resources'                  => 'http://www.php.net/get_resources',
                 'gc_mem_caches'                  => 'http://www.php.net/gc_mem_caches',
                 'preg_replace_callback_array'    => 'http://www.php.net/preg_replace_callback_array',
                 'posix_setrlimit'                => 'http://www.php.net/posix_setrlimit',
                 'random_bytes'                   => 'http://www.php.net/random_bytes',
                 'random_int'                     => 'http://www.php.net/random_int',
                 'intdiv'                         => 'http://www.php.net/intdiv',
                 'error_clear_last'               => 'http://www.php.net/error_clear_last',
                 'curl_share_strerror'            => 'http://www.php.net/curl_share_strerror',
                 'curl_multi_errno'               => 'http://www.php.net/curl_multi_errno',
                 'curl_share_errno'               => 'http://www.php.net/curl_share_errno',
                 'mb_ord'                         => 'http://www.php.net/mb_ord',
                 'mb_chr'                         => 'http://www.php.net/mb_chr',
                 'mb_scrub'                       => 'http://www.php.net/mb_scrub',
                 'is_iterable'                    => 'http://www.php.net/is_iterable',

                 'call_user_func_array'           => 'http://www.php.net/call_user_func_array',
                 'call_user_func'                 => 'http://www.php.net/call_user_func',

                 'strlen'                         => 'http://www.php.net/strlen',
                 'mb_strlen'                      => 'http://www.php.net/mb_strlen',
                 'grapheme_strlen'                => 'http://www.php.net/grapheme_strlen',
                 'iconv_strlen'                   => 'http://www.php.net/iconv_strlen',
                 'empty'                          => 'http://www.php.net/empty',

                 'usort'                          => 'http://www.php.net/usort',
                 'uksort'                         => 'http://www.php.net/uksort',
                 'uasort'                         => 'http://www.php.net/uasort',
                 'sort'                           => 'http://www.php.net/sort',

                 'mail'                           => 'http://www.php.net/mail',

                 'header'                         => 'http://www.php.net/header',
                 'exit'                           => 'http://www.php.net/exit',
                 'die'                            => 'http://www.php.net/die',

                 'exec'                           => 'http://www.php.net/exec',
                 'eval'                           => 'http://www.php.net/eval',
                 'pcntl_exec'                     => 'http://www.php.net/pcntl_exec',

                 'mb_substr'                      => 'http://www.php.net/mb_substr',
                 'mb_ord'                         => 'http://www.php.net/mb_ord',
                 'mb_chr'                         => 'http://www.php.net/mb_chr',
                 'mb_scrub'                       => 'http://www.php.net/mb_scrub',
                 'is_iterable'                    => 'http://www.php.net/is_iterable',
                 
                 'get_class'                      => 'http://www.php.net/get_class',
                 'sys_get_temp_dir'               => 'http://php.net/manual/en/function.sys-get-temp-dir.php',
 
                 'switch()'                       => 'http://php.net/manual/en/control-structures.switch.php',
                 'for()'                          => 'http://php.net/manual/en/control-structures.for.php',
                 'foreach()'                      => 'http://php.net/manual/en/control-structures.foreach.php',
                 'while()'                        => 'http://php.net/manual/en/control-structures.while.php',
                 'do..while()'                    => 'http://php.net/manual/en/control-structures.do.while.php',
   
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
while($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $liste = explode(',',$row['analyzers']);
    foreach($liste as &$a) {
        $name = $a;
        $ini = parse_ini_file("human/en/$a.ini");
        $commandLine = $a;

        $a = rst_link($ini['name']);

//        $ini['description'] = rst_link($ini['description']);
        
        $desc = glossary($ini['name'],$ini['description']);
        $desc = trim(rst_escape($desc));

        if (!empty($ini['clearphp'])) {
            $clearPHP = "`$ini[clearphp] <https://github.com/dseguy/clearPHP/tree/master/rules/$ini[clearphp].md>`__";
        } else {
            $clearPHP = '';
        }

        if (isset($a2themes[$name])) {
            $c = array_map('rst_link',$a2themes[$name]);
            $recipes = implode(',',$c);
        } else {
            $recipes = 'none';
        }
        
        $lineSize    = max(strlen($commandLine),strlen($clearPHP),strlen($recipes));
        $commandLine = str_pad($commandLine,$lineSize,' ');
        $recipes     = str_pad($recipes,$lineSize,' ');
        $separator   = '+--------------+-'.str_pad('',$lineSize,'-').'-+';
        if (!empty($clearPHP)) {
            $clearPHP    = '| clearPHP     | '.str_pad($clearPHP,$lineSize,' ')." |\n$separator\n";
        }

        $desc .= <<<RST


$separator
| Command Line | $commandLine |
$separator
$clearPHP| Analyzers    | $recipes |
$separator


RST;


        $analyzers[$ini['name']] = $desc;
    }
    unset($a);

    sort($liste);
    $text .= rst_level($row['name'],4)."\nTotal : ".count($liste)." analysis\n\n* ".implode("\n* ",$liste)."\n\n";
}

$rules = '';
ksort($analyzers);
foreach($analyzers as $title => $desc) {
    $rules .= rst_level($title,3)."\n\n$desc\n\n";
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
foreach($glossary as $letters => $items) {
    $found += count(array_keys($items));
}
print "$found found\n";
print count($entries)." defined\n";

foreach($entries as $name => $url) {
    $letter = strtoupper(trim($name,'\\`'))[0];
    if (!isset($glossary[$letter][$name])) {
        print $name." $letter\n";
    }
}

foreach($glossary as $letter => $items) {
    $glossaryRst .= "+ `$letter`\n";
    ksort($items);
    foreach($items as $key => $urls) {
        ksort($urls);
        $glossaryRst .= "    + `".stripslashes($key)."`\n
      + ".join("\n      + ",array_keys($urls))."\n\n";
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

//    $r = str_replace(array('*','|'),array('\\*','\\|'),$r);
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
        $glossary[$letter][$r[2]][":ref:`$title <".rst_anchor($title).">`"] = 1;
        
        if (isset($entries[$r[2]])) {
            $url = $entries[$r[2]];
            return $r[1]."`$r[2]$r[3] <$url>`_";//.$r[4];
        } else {
//            print "Nothing for ".$r[2]."\n";
            return $r[0];
        }

    };
    
    $description = preg_replace_callback('@([^a-zA-Z_])('.$alt.')(\(?\)?)(?=[^a-zA-Z_=])@is',$cbGlossary,' '.$description);
    /*,$r
    foreach($entries as $keyword => $url) {
        $letter = strtoupper(trim($keyword,'\\`'))[0];

        $regex = preg_quote($keyword);
        if (preg_match('![^a-zA-Z`_]'.$regex.'[^a-zA-Z_]!is',$description,$r)) {
            $glossary[$letter][$keyword][":ref:`$title <".rst_anchor($title).">`"] = 1;
            $description = preg_replace('!'.$regex.'(\S*)!is',"`$keyword\$1 <$url>`_",$description);
        }
    }
    */
    if ($title == 'Use random_int()') {
//        print $description;
    }
    
    
    return $description;
}

function generateAnalyzerList() {
    $files = glob('./human/en/*/*.ini');
    
    $versions = array();
    foreach($files as $file) {
        $ini = parse_ini_file($file);
        if (empty($ini['exakatSince'])) {
            print $file."\n";
            continue;
        }
        if (isset($versions[$ini['exakatSince']])) {
            $versions[$ini['exakatSince']][] = $ini['name'].' ('.basename(dirname($file)).'/'.substr(basename($file), 0, -4).')';
        } else {
            $versions[$ini['exakatSince']] = array($ini['name'].' ('.basename(dirname($file)).'/'.substr(basename($file), 0, -4).')');
        }
    }
    krsort($versions);
    
    $list = "\n";
    foreach($versions as $version => $analyzers) {
        $list .= '* '.$version."\n";
        sort($analyzers);
        $list .= '  * '.implode("\n  * ", $analyzers)."\n";
    }
    $list .= "\n";

    return $list;
}

?>