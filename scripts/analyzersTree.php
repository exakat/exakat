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

include dirname(__DIR__).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_library');

$analyzers = \Analyzer\Analyzer::getThemeAnalyzers();

$tree = array();
$names = array();
foreach($analyzers as $name) {
//    print "$name\n";
    $names[] = $name;
    $tree[$name] = array();
    $a = \Analyzer\Analyzer::getInstance($name);
    if ($a === null) { print $name."\n"; continue; }
    $d = $a->dependsOn();
    
//    if (empty($d)) { continue; }
    $tree[$name] = $d;
    
//    break 1;
}

        $nodes = '';
        foreach($names as $id => $name) {
            $nodes .= <<<DOT
$id [label="$name"];

DOT;
        }
        
        $names = array_flip($names);
        $links = '';
        foreach($tree as $o => $ds) {
            foreach($ds as $d) {
                if (!isset($names[$d])) {
                    print "Missing $d\n";
                    continue;
                }
                $links .= <<<DOT
$names[$d] -> $names[$o];

DOT;

//var_dump($links);die();
            }
        }
        
        $dot = "digraph graphname {\n        
        fontname = \"Bitstream Vera Sans\"
        fontsize = 14
        colorscheme = \"bugn9\"

        node [
                fontname = \"Bitstream Vera Sans\"
                fontsize = 14
                shape = \"record\"
        ]

        edge [
                fontname = \"Bitstream Vera Sans\"
                fontsize = 8
                arrowhead = \"empty\"
                width = \"2\"
        ]
        
        $nodes
        $links
        }
        ";//.implode("\n", $nodes['class'])."\n".implode("\n", $nodes['trait'])."\n".implode("\n", $nodes['interface'])."\n".implode("\n", $nodes['unknown']).
        file_put_contents('analysis.dot', $dot);

//print_r($tree);

?>