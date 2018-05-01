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

namespace Exakat\Reports;

use Exakat\Graph\Gremlin3;
use Exakat\Graph\GSNeo4j;
use Exakat\Config;

class Dependencies extends Reports {
    const FILE_EXTENSION = 'dot';
    const FILE_FILENAME  = 'dependencies';

    public function generate($folder, $name= 'dependencies') {
        display("This report is not finished\n");
        return;
        $graph = new GSNeo4j($this->config);

        $links    = array();
        $nodes    = array('class' => array(), 'trait' => array(), 'interface' => array(), 'unknown' => array());
        $fullcode = array();

        $query = <<<GREMLIN
g.V().hasLabel("Class").map{[it.get().value("fullnspath"), it.get().value("fullcode")]}
GREMLIN;
        $res = $graph->query($query);
        foreach($res as $v) {
            $v = (array) $v;
            $names[$v[0]] = $v[1];
            $nodes['class'][] = $v[0];
        }

        $query = <<<GREMLIN
g.V().hasLabel("Trait").map{[it.get().value("fullnspath"), it.get().value("fullcode")]}
GREMLIN;
        $res = $graph->query($query);
        foreach($res as $v) {
            $v = (array) $v;
            $names[$v[0]] = $v[1];
            $nodes['trait'][] = $v[0];
        }
        $query = <<<GREMLIN
g.V().hasLabel("Interface").map{[it.get().value("fullnspath"), it.get().value("fullcode")]}
GREMLIN;
        $res = $graph->query($query);
        foreach($res as $v) {
            $v = (array) $v;

            $names[$v[0]] = $v[1];
            $nodes['interface'][] = $v[0];
        }
        $nodesId = array_flip(call_user_func_array('array_merge', array_values($nodes)));

        // static constants
        $query = <<<GREMLIN
g.V().hasLabel("Staticconstant").as('fullcode')
.out('CLASS').as('destination')
.repeat(__.in()).until(hasLabel("Class", "Trait", "Interface")).as('origin')
.select('origin', 'destination', 'fullcode').by('fullnspath').by('fullnspath').by('fullcode')
GREMLIN;

        $res = $graph->query($query);
        $total = 0;
        foreach($res as $v) {
            $v = (array) $v;
            if (!isset($nodesId[$v['origin']])) {
                $nodes['unknown'][] = $v['origin'];
                $nodesId[$v['origin']] = count($nodes) - 1;
            }
            if (!isset($nodesId[$v['destination']])) {
                $nodes['unknown'][] = $v['destination'];
                $nodesId[$v['destination']] = count($nodes) - 1;
            }
            $links[$nodesId[$v['destination']].' -> '.$nodesId[$v['origin']]][] = 'staticconstant';
            $fullcode[$nodesId[$v['destination']].' -> '.$nodesId[$v['origin']]][] = $v['fullcode'];
            ++$total;
        }
        display( $total.' Static constants');

        // static property
        $query = <<<GREMLIN
g.V().hasLabel("Staticproperty").as('fullcode')
.out('CLASS').as('destination')
.repeat(__.in()).until(hasLabel("Class", "Trait", "Interface")).as('origin')
.select('origin', 'destination', 'fullcode').by('fullnspath').by('fullnspath').by('fullcode')
GREMLIN;

        $res = $graph->query($query);
        $total = 0;

        foreach($res as $v) {
            $v = (array) $v;
            if (!isset($nodesId[$v['origin']])) {
                $nodes['unknown'][] = $v['origin'];
                $nodesId[$v['origin']] = count($nodes) - 1;
            }
            if (!isset($nodesId[$v['destination']])) {
                $nodes['unknown'][] = $v['destination'];
                $nodesId[$v['destination']] = count($nodes) - 1;
            }
            $links[$nodesId[$v['destination']].' -> '.$nodesId[$v['origin']]][] = 'staticproperty';
            $fullcode[$nodesId[$v['destination']].' -> '.$nodesId[$v['origin']]][] = $v['fullcode'];
            ++$total;
        }
        display( $total.' Static constants');

        // Instantiation
        $query = <<<GREMLIN
g.V().hasLabel("New").as('fullcode')
.out('NEW').as('destination')
.has('fullnspath')
.repeat(__.in()).until(hasLabel("Class", "Trait", "Interface")).as('origin')
.select('origin', 'destination', 'fullcode').by('fullnspath').by('fullnspath').by('fullcode')
GREMLIN;
        $res = $graph->query($query);
        $total = 0;

        foreach($res as $v) {
            $v = (array) $v;
            if (!isset($nodesId[$v['origin']])) {
                $nodes['unknown'][] = $v['origin'];
                $nodesId[$v['origin']] = count($nodes) - 1;
            }
            if (!isset($nodesId[$v['destination']])) {
                $nodes['unknown'][] = $v['destination'];
                $nodesId[$v['destination']] = count($nodes) - 1;
            }
            $links[$nodesId[$v['destination']].' -> '.$nodesId[$v['origin']]][] = 'instanciation';
            $fullcode[$nodesId[$v['destination']].' -> '.$nodesId[$v['origin']]][] = $v['fullcode'];
            ++$total;
        }
        display( $total. ' New');

        // Typehint
        $query = <<<GREMLIN
g.V().hasLabel("Function").as("fullcode")
.out("ARGUMENT").out("TYPEHINT").as("destination")
.repeat(__.in()).until(hasLabel("Class", "Trait", "Interface")).as("origin")
.select("origin", "destination", "fullcode").by("fullnspath").by("fullnspath").by("fullcode")

GREMLIN;
        $res = $graph->query($query);
        $total = 0;
        foreach($res as $v) {
            $v = (array) $v;
            if (!isset($nodesId[$v['origin']])) {
                $nodes['unknown'][] = $v['origin'];
                $nodesId[$v['origin']] = count($nodes) - 1;
            }
            if (!isset($nodesId[$v['destination']])) {
                $nodes['unknown'][] = $v['destination'];
                $nodesId[$v['destination']] = count($nodes) - 1;
            }
            $links[$nodesId[$v['destination']].' -> '.$nodesId[$v['origin']]][] = 'typehint';
            $fullcode[$nodesId[$v['destination']].' -> '.$nodesId[$v['origin']]][] = $v['fullcode'];
            ++$total;
        }
        display( $total. ' Typehint');

        // instanceof
        $query = <<<GREMLIN
g.V().hasLabel("Instanceof").as('fullcode')
.out('CLASS').as('destination')
.has('fullnspath')
.repeat(__.in()).until(hasLabel("Class", "Trait", "Interface")).as('origin')
.select('origin', 'destination', 'fullcode').by('fullnspath').by('fullnspath').by('fullcode')

GREMLIN;
        $res = $graph->query($query);
        $total = 0;
        foreach($res as $v) {
            $v = (array) $v;
            if (!isset($nodesId[$v['origin']])) {
                $nodes['unknown'][] = $v['origin'];
                $nodesId[$v['origin']] = count($nodes) - 1;
            }
            if (!isset($nodesId[$v['destination']])) {
                $nodes['unknown'][] = $v['destination'];
                $nodesId[$v['destination']] = count($nodes) - 1;
            }
            $links[$nodesId[$v['destination']].' -> '.$nodesId[$v['origin']]][] = 'instanceof';
            $fullcode[$nodesId[$v['destination']].' -> '.$nodesId[$v['origin']]][] = $v['fullcode'];
            ++$total;
        }
        display( $total. ' Instanceof');

        // static methods
        $query = <<<GREMLIN
g.V().hasLabel("Staticmethodcall").as('fullcode')
.out('CLASS').as('destination')
.has('fullnspath')
.repeat(__.in()).until(hasLabel("Class", "Trait", "Interface")).as('origin')
.select('origin', 'destination', 'fullcode').by('fullnspath').by('fullnspath').by('fullcode')
GREMLIN;
        $res = $graph->query($query);
        $total = 0;
        foreach($res as $v) {
            $v = (array) $v;
            if (!isset($nodesId[$v['origin']])) {
                $nodes['unknown'][] = $v['origin'];
                $nodesId[$v['origin']] = count($nodes) - 1;
            }
            if (!isset($nodesId[$v['destination']])) {
                $nodes['unknown'][] = $v['destination'];
                $nodesId[$v['destination']] = count($nodes) - 1;
            }
            $links[$nodesId[$v['destination']].' -> '.$nodesId[$v['origin']]][] = 'staticmethodcall';
            $fullcode[$nodesId[$v['destination']].' -> '.$nodesId[$v['origin']]][] = $v['fullcode'];
            ++$total;
        }
        display( $total. ' Static methods');

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
                $linksDot[] = $link.' [shape="none" color="'.$colors[$t].'" label="'.str_replace('"', '\\"', $fullcode[$link][$id]).'"];';
            }
        }
        unset($type);

        $dot = <<<DOT
digraph graphname {        
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
    
DOT
    .implode(PHP_EOL, $nodes['class'])      .PHP_EOL
    .implode(PHP_EOL, $nodes['trait'])      .PHP_EOL
    .implode(PHP_EOL, $nodes['interface'])  .PHP_EOL
    .implode(PHP_EOL, $nodes['unknown'])    .PHP_EOL
    .PHP_EOL
    .implode(PHP_EOL, $linksDot).PHP_EOL.'}'.PHP_EOL;

        file_put_contents($folder.'/'.$name.'.'.self::FILE_EXTENSION, $dot);
    }
}

?>