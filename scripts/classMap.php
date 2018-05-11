#!/usr/bin/env php
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

include_once(dirname(__DIR__).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_library');

$config = new \Exakat\Config(array());
\Exakat\Analyzer\Analyzer::$staticConfig = $config;

/**
  * TODO : support interfaces/traits/extended classes
  * TODO : Colors methods/properties with visibility
  * TODO : differentiate Reads/Writes when accessing properties
*/


use Exakat\Graph\GraphResults;

$graphDB = new \Exakat\Graph\Tinkergraph($config);

$classes = array();

////////////////////////////////////////////////////////////////////////////////////////////////
/// Static constantes
$query = <<<'QUERY'
g.V().hasLabel("Class").as('c').sideEffect{ fnp = it.get().value("fullnspath"); }.out('METHOD')
.as('method').out('BLOCK').repeat( __.out()).emit().times(8).hasLabel('Staticconstant').where( __.out('CLASS').has("fullnspath").filter{ it.get().value("fullnspath") == fnp}).out('CONSTANT')
.as('call').select('c', 'method', 'call')
.by( out('NAME').values('fullcode') ).by(out('NAME').values('fullcode')).by('fullcode').unique()
QUERY;

$res = $graphDB->query($query);

print count($res).' class constants'.PHP_EOL;

foreach($res as $k => $v) {
    if (isset($classes[$v['c']]['constants'][$v['method']])) {
        $classes[$v['c']]['constants'][$v['method']][] = $v['call'];
    } elseif (isset($classes[$v['c']]['method'])) {
        $classes[$v['c']]['constants'][$v['method']] = [$v['call']];
    } elseif (isset($classes[$v['c']]) ) {
        $classes[$v['c']]['constants'][$v['method']] = [$v['call']];
    } else {
        $classes[$v['c']]['constants'][$v['method']] = [$v['call']];
    }
}

////////////////////////////////////////////////////////////////////////////////////////////////
/// Normal methods

$query = <<<'QUERY'
g.V().hasLabel("Class").as('c').out('METHOD')
.as('method').out('BLOCK').repeat( __.out()).emit().times(8).hasLabel('Methodcall').where( __.out('OBJECT').hasLabel('This')).out('METHOD')
.as('call').select('c', 'method', 'call')
.by( out('NAME').values('fullcode') ).by(out('NAME').values('fullcode')).by(out('NAME').values('fullcode')).unique()
QUERY;

$res = $graphDB->query($query);

print count($res).' links'.PHP_EOL;

foreach($res as $k => $v) {
    if (isset($classes[$v['c']]['links'][$v['method']])) {
        $classes[$v['c']]['links'][$v['method']][] = $v['call'];
    } elseif (isset($classes[$v['c']]['method'])) {
        $classes[$v['c']]['links'][$v['method']] = [$v['call']];
    } elseif (isset($classes[$v['c']]) ) {
        $classes[$v['c']]['links'][$v['method']] = [$v['call']];
    } else {
        $classes[$v['c']]['links'][$v['method']] = [$v['call']];
    }
}

////////////////////////////////////////////////////////////////////////////////////////////////
/// Static methods

$query = <<<'QUERY'
g.V().hasLabel("Class").sideEffect{ fnp = it.get().value("fullnspath"); }.as('c').out('METHOD')
.as('method').out('BLOCK').repeat( __.out()).emit().times(8).hasLabel('Staticmethodcall').where( __.out('CLASS').has("fullnspath").filter{ it.get().value("fullnspath") == fnp}).out('METHOD')
.as('call').select('c', 'method', 'call')
.by( out('NAME').values('fullcode') ).by(out('NAME').values('fullcode')).by(out('NAME').values('fullcode')).unique()
QUERY;

$res = $graphDB->query($query);

print count($res).' static links'.PHP_EOL;

foreach($res as $k => $v) {
    if (isset($classes[$v['c']]['links'][$v['method']])) {
        $classes[$v['c']]['links'][$v['method']][] = $v['call'];
    } elseif (isset($classes[$v['c']]['method'])) {
        $classes[$v['c']]['links'][$v['method']] = [$v['call']];
    } elseif (isset($classes[$v['c']]) ) {
        $classes[$v['c']]['links'][$v['method']] = [$v['call']];
    } else {
        $classes[$v['c']]['links'][$v['method']] = [$v['call']];
    }
}

////////////////////////////////////////////////////////////////////////////////////////////////
/// Static property

$query = <<<'QUERY'
g.V().hasLabel("Class").as('c').sideEffect{ fnp = it.get().value("fullnspath"); }.out('METHOD')
.as('method').out('BLOCK').repeat( __.out()).emit().times(8).hasLabel('Staticproperty').where( __.out('CLASS').has("fullnspath").filter{ it.get().value("fullnspath") == fnp}).out('MEMBER')
.as('property').select('c', 'method', 'property')
.by( out('NAME').values('fullcode') ).by(out('NAME').values('fullcode')).by('fullcode').unique()
QUERY;

$res = $graphDB->query($query);

print count($res).' static properties'.PHP_EOL;

foreach($res as $k => $v) {
    $v['property'] = substr($v['property'], 1);
    if (isset($classes[$v['c']]['property'][$v['property']])) {
        $classes[$v['c']]['property'][$v['property']][] = $v['method'];
    } elseif (isset($classes[$v['c']]['property'])) {
        $classes[$v['c']]['property'][$v['property']] = [$v['method']];
    } elseif (isset($classes[$v['c']]) ) {
        $classes[$v['c']]['property'][$v['property']] = [$v['method']];
    } else {
        $classes[$v['c']]['property'][$v['property']] = [$v['method']];
    }
}

////////////////////////////////////////////////////////////////////////////////////////////////
/// Normal Property

$query = <<<'QUERY'
g.V().hasLabel("Class").as('c').out('METHOD')
.as('method').out('BLOCK').repeat( __.out()).emit().times(8).hasLabel('Member').where( __.out('OBJECT').hasLabel('This')).out('MEMBER')
.as('property').select('c', 'method', 'property')
.by( out('NAME').values('fullcode') ).by(out('NAME').values('fullcode')).by('fullcode').unique()
QUERY;

$res = $graphDB->query($query);

print count($res).' properties'.PHP_EOL;

foreach($res as $k => $v) {
    if (isset($classes[$v['c']]['property'][$v['property']])) {
        $classes[$v['c']]['property'][$v['property']][] = $v['method'];
    } elseif (isset($classes[$v['c']]['property'])) {
        $classes[$v['c']]['property'][$v['property']] = [$v['method']];
    } elseif (isset($classes[$v['c']]) ) {
        $classes[$v['c']]['property'][$v['property']] = [$v['method']];
    } else {
        $classes[$v['c']]['property'][$v['property']] = [$v['method']];
    }
}

////////////////////////////////////////////////////////////////////////////////////////////////
/// All methods 

$query = <<<'QUERY'
g.V().hasLabel("Class").as('c').out('METHOD').as('method')
.select('c', 'method').by( out('NAME').values('fullcode') ).by(out('NAME').values('fullcode')).unique()
QUERY;

$res = $graphDB->query($query);

print count($res).' methods'.PHP_EOL;

foreach($res as $k => $v) {
    if (isset($classes[$v['c']]['methods']) ) {
        $classes[$v['c']]['methods'][] = $v['method'];
    } elseif (isset($classes[$v['c']]) ) {
        $classes[$v['c']]['methods'] = [$v['method']];
    } else {
        $classes[$v['c']]['methods'] = [$v['method']];
    }
}

$classes = array_slice($classes, 0, 10);

$dot = 'digraph {';
$i = 1;
$methods = array();
foreach($classes as $name => $m) {
//    $dot .= ++$i.' [ label="'.$name.'" fillcolor="crimson" style="filled"];'.PHP_EOL;
    $dot .= 'subgraph cluster_'.$i.' { 
        style=filled;
        label="'.$name.'";
        color="lightgrey";
';
    $classId = $i;

    foreach($m['methods'] as $method) {
        $dot .= ++$i.' [ label="'.addslashes($method).'" shape="square" style="filled" fillcolor="darkturquoise"];'.PHP_EOL;
        $methods[$name][$method] = $i;
//        $dot .= $classId .' -> '.$i.' [label="METHOD" color="blue3"];'.PHP_EOL;
    }

    if (isset($m['property'])) {
        foreach($m['property'] as $property => $values) {
            $dot .= ++$i.' [ label="$'.addslashes($property).'" shape="circle" style="filled" fillcolor="darkolivegreen1"];'.PHP_EOL;
            $methods[$name]['$'.$property] = $i;
//            $dot .= $classId .' -> '.$i.' [label="PROPERTY" color="blue3"];'.PHP_EOL;
        }
    }

    if (isset($m['constants'])) {
        foreach($m['constants'] as $constant => $values) {
            $dot .= ++$i.' [ label="$'.addslashes($constant).'" shape="hexagon" style="filled" fillcolor="darkgoldenrod"];'.PHP_EOL;
            $methods[$name][$constant] = $i;
//            $dot .= $classId .' -> '.$i.' [label="CONSTANT" color="blue3"];'.PHP_EOL;
        }
    }
    
    $dot .= '}';
}

foreach($classes as $name => $m) {
     if (!isset($m['links'])) { continue; }
    foreach($m['links'] as $o => $links) {
        foreach($links as $d) {
            if (!isset($methods[$name][$d], $methods[$name][$o])) { continue; }
            $dot .= $methods[$name][$o] .' -> '.$methods[$name][$d].' [label="CALL"];'.PHP_EOL;
        }
    }
}

foreach($classes as $name => $m) {
     if (!isset($m['property'])) { continue; }
    foreach($m['property'] as $o => $method) {
        foreach($method as $d) {
            if (!isset($methods[$name][$d], $methods[$name]['$'.$o])) { continue; }
            $dot .= $methods[$name][$d] .' -> '.$methods[$name]['$'.$o].' [label="USES"];'.PHP_EOL;
        }
    }
}

$dot .= '}';

print file_put_contents('classmap.dot', $dot).' octets'.PHP_EOL;

//print_r($classes);

?>