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
            ];

$text = '';
$recipesList = '"'.join('", "', $recipes).'"';

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

?>