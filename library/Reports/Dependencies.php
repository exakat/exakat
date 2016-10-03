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

namespace Reports;

class Dependencies extends Reports {
    CONST FILE_EXTENSION = 'dot';

    public function __construct() {
        parent::__construct();
    }

    public function generateFileReport($report) {
        return false;
    }

    public function generate($folder, $name= 'dependencies') {
        $graph = new \Graph\Gremlin2(\Exakat\Config::factory());
        
        $links    = [];
        $nodes    = ['class' => [], 'trait' => [], 'interface' => [], 'unknown' => []];
        $fullcode = [];
        
        $query = <<<GREMLIN
g.V().hasLabel("Class").map{[it.get().value("fullnspath"), it.get().value("fullcode")]}
GREMLIN;
        $res = $graph->query($query);
        foreach($res->results as $v) {
            $names[$v[0]] = $v[1];
            $nodes['class'][] = $v[0];
        }
        $query = <<<GREMLIN
g.V().hasLabel("Trait").map{[it.get().value("fullnspath"), it.get().value("fullcode")]}
GREMLIN;
        $res = $graph->query($query);
        foreach($res->results as $v) {
            $names[$v[0]] = $v[1];
            $nodes['trait'][] = $v[0];
        }
        $query = <<<GREMLIN
g.V().hasLabel("Interface").map{[it.get().value("fullnspath"), it.get().value("fullcode")]}
GREMLIN;
        $res = $graph->query($query);
        foreach($res->results as $v) {
            $names[$v[0]] = $v[1];
            $nodes['interface'][] = $v[0];
        }
        $nodesId = array_flip(call_user_func_array('array_merge', array_values($nodes)));
        
        // static constants
        $query = <<<GREMLIN
g.idx('atoms')[['atom':'Staticconstant']].sideEffect{ fullcode = it.fullcode;}.out('CLASS').sideEffect{ origin = it.fullnspath;}.filter{ !(it.code in ['self'])}.in('CLASS')
.in().loop(1){!(it.object.atom in ['Class', 'Interface'])}{it.object.atom in ['Class', 'Interface']}
.filter{ origin != it.fullnspath}
.transform{ [origin, it.fullnspath, fullcode]};

GREMLIN;
        $res = $graph->query($query);
        $total = 0;
        foreach($res->results as $v) {
            if (!isset($nodesId[$v[0]])) { 
                $nodes['unknown'][] = $v[0];
                $nodesId[$v[0]] = count($nodes) - 1;
            }
            if (!isset($nodesId[$v[1]])) { 
                $nodes['unknown'][] = $v[1];
                $nodesId[$v[1]] = count($nodes) - 1;
            }
            $links[$nodesId[$v[1]].' -> '.$nodesId[$v[0]]][] = 'staticconstant';
            $fullcode[$nodesId[$v[1]].' -> '.$nodesId[$v[0]]][] = $v[2];
            ++$total;
        }
        print "$total Static constants\n";

        // static property
        $query = <<<GREMLIN
g.idx('atoms')[['atom':'Staticproperty']].sideEffect{ fullcode = it.fullcode;}.out('CLASS').sideEffect{ origin = it.fullnspath;}.filter{ !(it.code in ['self'])}.in('CLASS')
.in().loop(1){!(it.object.atom in ['Class', 'Trait'])}{it.object.atom in ['Class', 'Trait']}
.filter{ origin != it.fullnspath}
.transform{ [origin, it.fullnspath, fullcode]};

GREMLIN;
        $res = $graph->query($query);
        $total = 0;
        foreach($res->results as $v) {
            if (!isset($nodesId[$v[0]])) { 
                $nodes['unknown'][] = $v[0];
                $nodesId[$v[0]] = count($nodes) - 1;
            }
            if (!isset($nodesId[$v[1]])) { 
                $nodes['unknown'][] = $v[1];
                $nodesId[$v[1]] = count($nodes) - 1;
            }
            $links[$nodesId[$v[1]].' -> '.$nodesId[$v[0]]][] = 'staticproperty';
            $fullcode[$nodesId[$v[1]].' -> '.$nodesId[$v[0]]][] = $v[2];
            ++$total;
        }
        print "$total Static property\n";

        // Instantiation
        $query = <<<GREMLIN
g.idx('atoms')[['atom':'New']].sideEffect{ fullcode = it.fullcode;}.out('NEW').hasNot('fullnspath', null).filter{ !(it.code in ['self'])}.sideEffect{ origin = it.fullnspath;}
.in().loop(1){!(it.object.atom in ['Class', 'Trait'])}{it.object.atom in ['Class', 'Trait']}
.filter{ origin != it.fullnspath}
.transform{ [origin, it.fullnspath, fullcode]};

GREMLIN;
        $res = $graph->query($query);
        $total = 0;
        foreach($res->results as $v) {
            if (!isset($nodesId[$v[0]])) { 
                $nodes['unknown'][] = $v[0];
                $nodesId[$v[0]] = count($nodes) - 1;
            }
            if (!isset($nodesId[$v[1]])) { 
                $nodes['unknown'][] = $v[1];
                $nodesId[$v[1]] = count($nodes) - 1;
            }
            $links[$nodesId[$v[1]].' -> '.$nodesId[$v[0]]][] = 'instanciation';
            $fullcode[$nodesId[$v[1]].' -> '.$nodesId[$v[0]]][] = $v[2];
            ++$total;
        }
        print "$total New\n";

        // Typehint
        $query = <<<GREMLIN
g.idx('atoms')[['atom':'Typehint']].sideEffect{ fullcode = it.fullcode;}.out('CLASS').sideEffect{ origin = it.fullnspath;}.filter{ !(it.code in ['self'])}.in('CLASS')
.in().loop(1){!(it.object.atom in ['Class'])}{it.object.atom in ['Class']}
.filter{ origin != it.fullnspath}
.transform{ [origin, it.fullnspath, fullcode]};

GREMLIN;
        $res = $graph->query($query);
        $total = 0;
        foreach($res->results as $v) {
            if (!isset($nodesId[$v[0]])) { 
                $nodes['unknown'][] = $v[0];
                $nodesId[$v[0]] = count($nodes) - 1;
            }
            if (!isset($nodesId[$v[1]])) { 
                $nodes['unknown'][] = $v[1];
                $nodesId[$v[1]] = count($nodes) - 1;
            }
            $links[$nodesId[$v[1]].' -> '.$nodesId[$v[0]]][] = 'typehint';
            $fullcode[$nodesId[$v[1]].' -> '.$nodesId[$v[0]]][] = $v[2];
            ++$total;
        }
        print "$total Typehint\n";

        // instanceof
        $query = <<<GREMLIN
g.idx('atoms')[['atom':'Instanceof']].sideEffect{ fullcode = it.fullcode;}.out('CLASS').sideEffect{ origin = it.fullnspath;}.filter{ !(it.code in ['self'])}.in('CLASS')
.in().loop(1){!(it.object.atom in ['Class', 'Trait'])}{it.object.atom in ['Class', 'Trait']}
.filter{ origin != it.fullnspath}
.transform{ [origin, it.fullnspath, fullcode]};

GREMLIN;
        $res = $graph->query($query);
        $total = 0;
        foreach($res->results as $v) {
            if (!isset($nodesId[$v[0]])) { 
                $nodes['unknown'][] = $v[0];
                $nodesId[$v[0]] = count($nodes) - 1;
            }
            if (!isset($nodesId[$v[1]])) { 
                $nodes['unknown'][] = $v[1];
                $nodesId[$v[1]] = count($nodes) - 1;
            }
            $links[$nodesId[$v[1]].' -> '.$nodesId[$v[0]]][] = 'instanceof';
            $fullcode[$nodesId[$v[1]].' -> '.$nodesId[$v[0]]][] = $v[2];
            ++$total;
        }
        print "$total Instanceof\n";

        // Use (for classes)
        $query = <<<GREMLIN
g.idx('atoms')[['atom':'Use']].sideEffect{ fullcode = it.fullcode;}.out('USE').sideEffect{ origin = it.fullnspath;}.in('USE')
.in('ELEMENT').in('BLOCK').filter{ it.atom in ['Trait', 'Class']}
.filter{ origin != it.fullnspath}
.transform{ [origin, it.fullnspath, fullcode]};

GREMLIN;
        $res = $graph->query($query);
        $total = 0;
        foreach($res->results as $v) {
            if (!isset($nodesId[$v[0]])) { 
                $nodes['unknown'][] = $v[0];
                $nodesId[$v[0]] = count($nodes) - 1;
            }
            if (!isset($nodesId[$v[1]])) { 
                $nodes['unknown'][] = $v[1];
                $nodesId[$v[1]] = count($nodes) - 1;
            }
            $links[$nodesId[$v[1]].' -> '.$nodesId[$v[0]]][] = 'use';
            $fullcode[$nodesId[$v[1]].' -> '.$nodesId[$v[0]]][] = $v[2];
            ++$total;
        }
        print "$total Use (for class or trait)\n";

        // static methods
        $query = <<<GREMLIN
g.idx('atoms')[['atom':'Staticmethodcall']].sideEffect{ fullcode = it.fullcode;}.out('CLASS').sideEffect{ origin = it.fullnspath;}.filter{ !(it.code in ['self'])}.in('CLASS')
.in().loop(1){!(it.object.atom in ['Class', 'Trait'])}{it.object.atom in ['Class', 'Trait']}
.filter{ origin != it.fullnspath}
.transform{ [origin, it.fullnspath, fullcode]};

GREMLIN;
        $res = $graph->query($query);
        $total = 0;
        foreach($res->results as $v) {
            if (!isset($nodesId[$v[0]])) { 
                $nodes['unknown'][] = $v[0];
                $nodesId[$v[0]] = count($nodes) - 1;
            }
            if (!isset($nodesId[$v[1]])) { 
                $nodes['unknown'][] = $v[1];
                $nodesId[$v[1]] = count($nodes) - 1;
            }
            $links[$nodesId[$v[1]].' -> '.$nodesId[$v[0]]][] = 'staticmethodcall';
            $fullcode[$nodesId[$v[1]].' -> '.$nodesId[$v[0]]][] = $v[2];
            ++$total;
        }
        print "$total Static methods\n";


// Final preparation
// Nodes
        $colors = array('class' => 'darkorange', 'trait' => 'gold', 'interface' => 'skyblue', 'unknown' => 'gray');
        foreach($nodes as $type => &$someNodes) {
            foreach($someNodes as $id => &$n) {
                $n = <<<DOT
$nodesId[$n] [label=<<table color='white' BORDER='0' CELLBORDER='1' CELLSPACING='0' >
                          <tr>
                              <td bgcolor='$colors[$type]'>$n</td>
                          </tr>
                       </table>> shape="none"];
DOT;
            }
            unset($n);
        }
        unset($someNodes);
        print_r($nodes);

// Links
        $colors = array('staticmethodcall' => 'firebrick2',
                        'staticconstant'   => 'firebrick2',
                        'staticproperty'   => 'firebrick2',
                        'instanceof'       => 'chartreuse4',
                        'typehint'         => 'chartreuse4',
                        'use'              => 'darkgoldenrod2',
                        'instanciation'    => 'black',
                        );
        $linksDot = array();
        foreach($links as $link => $type) {
            foreach($type as $id => $t) {
                $linksDot[] = $link.' [shape="none" color="'.$colors[$t].'" label="'.$fullcode[$link][$id].'"];';
            }
        }
        unset($type);


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
        
        ".implode("\n", $nodes['class'])."\n".implode("\n", $nodes['trait'])."\n".implode("\n", $nodes['interface'])."\n".implode("\n", $nodes['unknown']).
        "\n\n".implode("\n", $linksDot)."\n}\n";
        print strlen($dot);
        print $folder.'/'.$name.'.'.self::FILE_EXTENSION;
        
        file_put_contents($folder.'/'.$name.'.'.self::FILE_EXTENSION, $dot);
    }
}