<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

$recipes = ["Analyze",
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
            ];

$text = '';
$recipesList = '"'.join('", "', $recipes).'"';
$glossary = array();
$entries = array('preg\_replace'                  => 'http://www.php.net/preg_replace',
                 'preg\_match'                    => 'http://www.php.net/preg_match',
                 'preg\_replace\_callback\_array' => 'http://www.php.net/preg_replace_callback_array',
                 'pow'                            => 'http://www.php.net/pow',
                 'array\_unique'                  => 'http://www.php.net/array_unique',
                 'array\_count\_values'           => 'http://www.php.net/array_count_values',
                 'array\_flip'                    => 'http://www.php.net/array_flip',
                 'array\_keys'                    => 'http://www.php.net/array_keys',
                 'strpos'                         => 'http://www.php.net/strpos',
                 'stripos'                        => 'http://www.php.net/stripos',
                 'throw'                          => 'http://www.php.net/throw',
                 'curl\_share\_strerror'          => 'http://www.php.net/curl_share_strerror',
                 'curl\_multi\_errno'             => 'http://www.php.net/curl_multi_errno',
                 'random\_int'                    => 'http://www.php.net/random_int',
                 'random\_bytes'                  => 'http://www.php.net/random_bytes',
                 'rand'                           => 'http://www.php.net/rand',
                 'srand'                          => 'http://www.php.net/srand',
                 'mt\_rand'                       => 'http://www.php.net/mt_rand',
                 'mt\_srand'                      => 'http://www.php.net/mt_srand',
                 'set\_exception\_handler'        => 'http://www.php.net/set_exception_handler',

                 'strlen'                         => 'http://www.php.net/strlen',
                 'mb\_strlen'                     => 'http://www.php.net/mb_strlen',
                 'grapheme\_strlen'               => 'http://www.php.net/grapheme_strlen',
                 'iconv\_strlen'                  => 'http://www.php.net/iconv_strlen',
                 'empty'                          => 'http://www.php.net/empty',

                 'usort'                          => 'http://www.php.net/usort',
                 'uksort'                         => 'http://www.php.net/uksort',
                 'uasort'                         => 'http://www.php.net/uasort',
                 'sort'                           => 'http://www.php.net/sort',

                 'exec'                           => 'http://www.php.net/exec',
                 'eval'                           => 'http://www.php.net/eval',

                 'mb\_substr'                     => 'http://www.php.net/mb_substr',
                 'mb\_ord'                        => 'http://www.php.net/mb_ord',
                 'mb\_chr'                        => 'http://www.php.net/mb_chr',
                 'mb\_scrub'                      => 'http://www.php.net/mb_scrub',
                 'is\_iterable'                   => 'http://www.php.net/is_iterable',
                 
                 'get\_class'                     => 'http://www.php.net/get_class',
                 'sys\_get\_temp\_dir'            => 'http://php.net/manual/en/function.sys-get-temp-dir.php',
 
                 'switch()'                       => 'http://php.net/manual/en/control-structures.switch.php',
                 'for()'                          => 'http://php.net/manual/en/control-structures.for.php',
                 'foreach()'                      => 'http://php.net/manual/en/control-structures.foreach.php',
                 'while()'                        => 'http://php.net/manual/en/control-structures.while.php',
                 'do..while()'                    => 'http://php.net/manual/en/control-structures.do.while.php',
   
                 '`break`'                        => 'http://php.net/manual/en/control-structures.break.php',
                 '`continue`'                     => 'http://php.net/manual/en/control-structures.continue.php',
                 'instanceof'                     => 'http://php.net/manual/en/language.operators.type.php',
                 'insteadof'                      => 'http://php.net/manual/en/language.oop5.traits.php',
                    
                 '`**`'                           => 'http://php.net/manual/en/language.operators.arithmetic.php',
                 '$_GET'                          => 'http://php.net/manual/en/reserved.variables.get.php',
                 '$_POST'                         => 'http://php.net/manual/en/reserved.variables.post.php',
                 '$this'                          => 'http://php.net/manual/en/language.oop5.basic.php',
                  
                 '\_\_construct'                  => 'http://php.net/manual/en/language.oop5.decon.php',
                 '\_\_destruct'                   => 'http://php.net/manual/en/language.oop5.decon.php',
                  
                 '\_\_call'                       => 'http://php.net/manual/en/language.oop5.magic.php',
                 '\_\_callStatic'                 => 'http://php.net/manual/en/language.oop5.magic.php',
                 '\_\_get'                        => 'http://php.net/manual/en/language.oop5.magic.php',
                 '\_\_set'                        => 'http://php.net/manual/en/language.oop5.magic.php',
                 '\_\_isset'                      => 'http://php.net/manual/en/language.oop5.magic.php',
                 '\_\_unset'                      => 'http://php.net/manual/en/language.oop5.magic.php',
                 '\_\_sleep'                      => 'http://php.net/manual/en/language.oop5.magic.php',
                 '\_\_wakeup'                     => 'http://php.net/manual/en/language.oop5.magic.php',
                 '\_\_toString'                   => 'http://php.net/manual/en/language.oop5.magic.php',
                 '\_\_invoke'                     => 'http://php.net/manual/en/language.oop5.magic.php',
                 '\_\_set_state'                  => 'http://php.net/manual/en/language.oop5.magic.php',
                 '\_\_clone'                      => 'http://php.net/manual/en/language.oop5.magic.php',
                 '\_\_debugInfo'                  => 'http://php.net/manual/en/language.oop5.magic.php',
                 
                 'ArrayAccess'                    => 'http://php.net/manual/en/class.arrayaccess.php',
                 'Throwable'                      => 'http://php.net/manual/fr/class.throwable.php',
                 'Closure'                        => 'http://php.net/manual/fr/class.closure.php',
                 'Traversable'                    => 'http://php.net/manual/fr/class.traversable.php',
                 
                 '__FILE__'                       => 'http://php.net/manual/en/language.constants.predefined.php',
                 '__DIR__'                        => 'http://php.net/manual/en/language.constants.predefined.php',
                 '__LINE__'                       => 'http://php.net/manual/en/language.constants.predefined.php',
                 '__CLASS__'                      => 'http://php.net/manual/en/language.constants.predefined.php',
                 '__METHOD__'                     => 'http://php.net/manual/en/language.constants.predefined.php',
                 '__NAMESPACE__'                  => 'http://php.net/manual/en/language.constants.predefined.php',
                 '__TRAIT__'                      => 'http://php.net/manual/en/language.constants.predefined.php',
                 '__FUNCTION__'                   => 'http://php.net/manual/en/language.constants.predefined.php',

                 );

$sqlite = new \Sqlite3('data/analyzers.sqlite');

$query = 'SELECT a.folder || "/" || a.name AS analyzer, GROUP_CONCAT(c.name) analyzers  
                FROM categories c
                JOIN analyzers_categories ac
                    ON c.id = ac.id_categories
                JOIN analyzers a
                    ON a.id = ac.id_analyzer
                WHERE c.name IN ('.$recipesList.')
                GROUP BY a.name';
$res = $sqlite->query($query);
$a2themes = [];
while($row = $res->fetchArray(SQLITE3_ASSOC)) {
   $a2themes[$row['analyzer']] = explode(',', $row['analyzers']);
}

$query = 'SELECT c.name, GROUP_CONCAT(a.folder || "/" || a.name) analyzers  
                FROM categories c
                JOIN analyzers_categories ac
                    ON c.id = ac.id_categories
                JOIN analyzers a
                    ON a.id = ac.id_analyzer
                WHERE c.name IN ('.$recipesList.')
                GROUP BY c.name';

$res = $sqlite->query($query);
$analyzers = [];
while($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $liste = explode(',', $row['analyzers']);
    foreach($liste as &$a) {
        $name = $a;
        $ini = parse_ini_file("human/en/$a.ini");
        $commandLine = $a;

        $a = rst_link($ini['name']);

        $desc = trim(rst_escape($ini['description']));
        $ini['description'] = rst_link($ini['description']);
        
        $ini['description'] = glossary($ini['name'], $ini['description']);

        if (!empty($ini['clearphp'])) {
            $clearPHP = "`$ini[clearphp] <https://github.com/dseguy/clearPHP/tree/master/rules/$ini[clearphp].md>`__";
        } else {
            $clearPHP = '';
        }

        if (isset($a2themes[$name])) {
            $c = array_map('rst_link', $a2themes[$name]);
            $recipes = implode(', ', $c);
        } else {
            $recipes = 'none';
        }
        
        $lineSize = max(strlen($commandLine), strlen($clearPHP), strlen($recipes));
        $commandLine = str_pad($commandLine, $lineSize, ' ');
        $clearPHP    = str_pad($clearPHP,    $lineSize, ' ');
        $recipes     = str_pad($recipes,     $lineSize, ' ');
        $separator   = '+--------------+-'.str_pad('', $lineSize, '-').'-+';

        $desc .= <<<RST


$separator
| Command Line | $commandLine |
$separator
| clearPHP     | $clearPHP |
$separator
| Analyzers    | $recipes |
$separator


RST;


        $analyzers[$ini['name']] = $desc;
    }
    unset($a);

    sort($liste);
    $text .= rst_level($row['name'], 4)."\nTotal : ".count($liste)." analysis\n\n* ".implode("\n* ", $liste)."\n\n";
}

$rules = '';
ksort($analyzers);
foreach($analyzers as $title => $desc) {
    $rules .= rst_level($title, 3)."\n\n$desc\n\n";
}

$rst = file_get_contents('docs/Recipes.rst');
$date = date('r');
$hash = shell_exec('git rev-parse HEAD');
$rst = preg_replace('/.. comment: Recipes details(.*)$/is', ".. comment: Recipes details\n.. comment: Generation date : $date\n.. comment: Generation hash : $hash\n\n$text", $rst);
print file_put_contents('docs/Recipes.rst', $rst)." octets written for Recipes\n";

$rst = file_get_contents('docs/Rules.rst');
$rst = preg_replace('/.. comment: Rules details(.*)$/is', ".. comment: Rules details\n.. comment: Generation date : $date\n.. comment: Generation hash : $hash\n\n$rules", $rst);
print file_put_contents('docs/Rules.rst', $rst)." octets written for Rules\n";

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
    $letter = strtoupper(trim($name, '\\`'))[0];
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
      + ".join("\n      + ", array_keys($urls))."\n\n";
    }
    $glossaryRst .= "\n";
}
$glossaryRst .= "\n";
print file_put_contents('docs/Glossary.rst', $glossaryRst)." octets written for Rules\n";

function rst_anchor($name) {
    return str_replace(array(' ', '_', ':'), array('-', '\\_', '\\:'), strtolower($name));
}

function rst_anchor_def($name) {
    return '.. _'.rst_anchor($name).":\n\n";
}

function rst_escape($string) {
    $r = preg_replace_callback('/<\?php(.*?)\?>/is', function ($r) {
        $rst = ".. code-block:: php\n\n   ".str_replace("\n", "\n   ", $r[0])."\n";
        return $rst;
    }, $string);

    $r = preg_replace_callback('/\s*<\?literal(.*?)\?>/is', function ($r) {
        $rst = "::\n\n   ".str_replace("\n", "\n   ", $r[1])."\n";
        return $rst;
    }, $r);

    $r = str_replace(array('*', '|', '_'), array('\\*', '\\|', '\\_'), $r);
    
    return $r;
}

function rst_link($title) {
    if (strpos($title, ' ') !== false) {
        $escapeTitle = rst_anchor($title);
        return ':ref:`'.rst_escape($title).' <'.$escapeTitle.'>`';
    } else {
        return ':ref:`'.rst_escape($title).'`';
    }
}

function rst_level($title, $level = 1) {
    $levels = array(1 => '=', 2 => '-', 3 => '#', 4 => '+');
    $escapeTitle = rst_escape($title);
    return rst_anchor_def($title).$escapeTitle."\n".str_repeat($levels[$level], strlen($escapeTitle))."\n";
}

function glossary($title, &$description) {
    global $glossary, $entries;
    
    foreach($entries as $keyword => $url) {
        $letter = strtoupper(trim($keyword, '\\`'))[0];

        $regex = preg_quote($keyword);
        if (preg_match('!\W'.$regex.'\W!is', $description, $r)) {
            $glossary[$letter][$keyword][":ref:`$title <".rst_anchor($title).">`"] = 1;
            $description = preg_replace('!'.$regex.'!is', "`$keyword <$url>`_", $description);
        }
    }

    return $description;
}

?>