<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


$dot = file_get_contents('wordpress.dot');

preg_match_all('#(\d+) \[label="([^"]+?)" shape=box \]#is', $dot, $nodes);

$total = 0;
$fp = fopen('neo4j/wp_functions.csv', 'w+');
fputcsv($fp, array('Fid:ID(Function)', 'name'));
foreach($nodes[1] as $id => $n) {
    ++$total;
    fputcsv($fp, array($nodes[1][$id], $nodes[2][$id]));
}
fclose($fp);
print "Created $total nodes\n";

preg_match_all('#(\d+) -> (\d+) \[label="CALLS"\];#is', $dot, $rels);

$total = 0;
$fp = fopen('neo4j/wp_calls.csv', 'w+');
fputcsv($fp, array(':START_ID(Function)', ':TYPE', ':END_ID(Function)') );
foreach($rels[1] as $id => $n) {
    ++$total;
    fputcsv($fp, array($rels[1][$id], 'CALLS', $rels[2][$id]));
}
fclose($fp);
print "Created $total relations\n";

/*

--id-type id \


                 --relationships:ORDERED customer_orders_header.csv,orders1.csv,orders2.csv



./bin/neo4j stop
rm -rf data/graph.db
bin/neo4j-import --into data/graph.db \
                 --nodes:Function wp_functions.csv \
                 --relationships:CONTAINS wp_calls.csv 
./bin/neo4j start

*/
?>