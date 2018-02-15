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

$graphDB = new \Exakat\Graph\Gremlin3($config);

$query = <<<'QUERY'
g.V().hasLabel("Class").as('a').as('b').out('NAME').as('c').select('a')
    .not(where( out('IMPLEMENTS')))
     .where(__.out('BLOCK').out('ELEMENT').hasLabel('Ppp').out('PPP').count().is(gt(6)))
     .select('a','b','c').by('fullcode').by(__.out('BLOCK').out('ELEMENT').hasLabel('Ppp').out('PPP').count());
QUERY;

$res = $graphDB->query($query);

print count($res->results)." classes to display found\n";
foreach($res->results as $o) {
    processClass($o->c, $graphDB);
}

function processClass($class, $graphDB) {

    print "Class $class\n";
    $query = <<<QUERY
    g.V().hasLabel("Class")
    .out('NAME').has('code', '$class').in('NAME')
    .out('BLOCK').out('ELEMENT').out('PPP').values('propertyname');
    
QUERY;
    
    $res = $graphDB->query($query);
    //print_r($res);
    $properties = $res->results;
    $propertiesList = "'".implode("', '", $properties)."'";
    
    $query = <<<QUERY
    
    g.V().hasLabel("Class")
      .out('NAME').has('code', '$class').in('NAME')
      .out('BLOCK').out('ELEMENT').hasLabel("Function").as('f')
      .out('NAME').not(has('code', within('__construct'))).as('n')
      .select('f')
      .map(out('BLOCK').repeat( __.out()).emit(hasLabel("Property")).times(15).hasLabel('Property')
                       .where( __.out('PROPERTY').has('code', within(arg0)))
                       .groupCount().by(out("PROPERTY").values("code"))).as('p')
      .select('f')
      .map(out('BLOCK').repeat( __.out()).emit(hasLabel("Methodcall")).times(15).hasLabel('Methodcall')
                       .where( __.out('OBJECT').has('code', '\$this'))
                       .groupCount().by(out("METHOD").values("code"))).as('m')
      .select('f','p','m', 'n').by('fullcode').by().by().by('code')
    
QUERY;
    
    $res = $graphDB->query($query, array('arg0' => $properties));
    
    // Initialize matrix with 0
    $usage = array();
    foreach($properties as $p1) {
        foreach($properties as $p2) {
            $usage[$p1][$p2] = 0;
        }
    }
    
    // Build a dictionary of methods 
    $results = array();
    foreach($res->results as $method) {
        $results[$method->n] = $method;
    }
    
    // Collect usage of properties in each methods
    $methodsW2 = array();
    foreach($results as $method) {
        $methodsW2[$method->n] = 1;
        
        $propertiesInMethod = array(array_keys((array)$method->p));

        foreach($method->m as $m1 => $c) {
            if (isset($results[$m1])) {
                $propertiesInMethod[] = array_keys((array) $results[$m1]->p);
            }
        }

        $propertiesInMethod = array_merge(...$propertiesInMethod);
        $propertiesInMethod = array_keys(array_count_values($propertiesInMethod));
        
        foreach($propertiesInMethod as $p1) {
            foreach($propertiesInMethod as $p2) {
                ++$usage[$p1][$p2];
    //            $usage[$p1][$p2] = 1;
            }
        }
    } 
    
    // Collect usage in every subsequent methods.
    $usageL2 = $usage;
    foreach($res->results as $method) {
        foreach($method->m as $m1 => $c1) {
            if (isset($results[$m1])) {
                ++$methodsW2[$m1];
            }
        }
    } 
    
    foreach($methodsW2 as $mName => $weight) {
        $method = $results[$mName];
        foreach($method->p as $p1 => $c1) {
            foreach($method->p as $p2 => $c2) {
                $usageL2[$p1][$p2] += $weight;
    //            $usage[$p1][$p2] = 1;
            }
        }
    } 
    
    displayMatrix($usage, $properties, 'Properties only');
    displayMatrix($usageL2, $properties, 'Properties and 1rst level of methods');
}

function displayMatrix($usage, $properties, $title = "") {
    print "$title\n\n";

    // sorting
    $sortable = array();
    foreach($properties as $p) {
        $sortable[$p] = array_sum($usage[$p]);
    }
    asort($sortable);
    
    //print_r($usage);
    $col1Size = max(array_map('strlen', $properties));
    $col1Pad = str_repeat( ' ', $col1Size);
    foreach(array_keys($sortable) as $p1) {
        print substr($col1Pad.$p1, -$col1Size).' ';
        foreach(array_keys($sortable) as $p2) {
            print $usage[$p1][$p2].' ';
        }
        print "\n";
    }

    print "\n";
}
?>