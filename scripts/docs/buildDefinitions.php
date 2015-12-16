<?php

$text = '';

$sqlite = new \Sqlite3('data/analyzers.sqlite');

$query = 'SELECT a.folder || "/" || a.name AS analyzer, GROUP_CONCAT(c.name) analyzers  
                FROM categories c
                JOIN analyzers_categories ac
                    ON c.id = ac.id_categories
                JOIN analyzers a
                    ON a.id = ac.id_analyzer
                WHERE c.name IN ("Analyze",
                                 "CompatibilityPHP71",
                                 "CompatibilityPHP70",
                                 "CompatibilityPHP56",
                                 "CompatibilityPHP55",
                                 "CompatibilityPHP54",
                                 "CompatibilityPHP53",
                                 "Analyze",
                                 "Security",
                                 "Performances",
                                 "Dead code"
                                 )
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
                WHERE c.name IN ("Analyze",
                                 "CompatibilityPHP71",
                                 "CompatibilityPHP70",
                                 "CompatibilityPHP56",
                                 "CompatibilityPHP55",
                                 "CompatibilityPHP54",
                                 "CompatibilityPHP53",
                                 "Analyze",
                                 "Security",
                                 "Performances",
                                 "Dead code"
                                 )
                GROUP BY c.name';

$res = $sqlite->query($query);
$analyzers = [];
while($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $liste = explode(',', $row['analyzers']);
    foreach($liste as &$a) {
        $name = $a;
        $ini = parse_ini_file("human/en/$a.ini");
        $a = rst_link($ini['name']);

        $desc = trim(rst_escape($ini['description']));
        if (!empty($ini['clearphp'])) {
            $desc .= "\n\nclearPHP: `$ini[clearphp] <https://github.com/dseguy/clearPHP/tree/master/rules/$ini[clearphp].md>`__\n";
        }
        if (isset($a2themes[$name])) {
            $c = array_map('rst_link', $a2themes[$name]);
            $desc .= "\n\nThis analyzer is part of the following recipes :  ". join(', ', $c)."\n";
        } else {
//            print "$a\n";
        }
        $analyzers[$ini['name']] = $desc;
    }

    sort($liste);
    $text .= rst_level($row['name'], 4)."\nTotal : ".count($liste)." analysis\n\n* ".join("\n* ", $liste)."\n\n";
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
    $r = str_replace(array('_', '&', '<'), array('\\_', '&amp;', '&lt;'), $string);;

    return preg_replace('/(\$\S+)/is', '`$1`', $r);
}

function rst_link($title) {
//    return ':ref:`'.rst_escape($title).' <'.rst_anchor($title).'>`';
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