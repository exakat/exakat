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


$res = shell_exec('grep -r AlteringForeachWithoutReference projects/*/log/analyze.analyze.log');
print $res;

$finals = array();
$rows = explode("\n", trim($res));
foreach($rows as $row) {
    $final = array();
    
    //projects/anchor-cms/log//analyze.analyze.log:Structures/AlteringForeachWithoutReference	0.067572116851807	3	111	1	0
    if (!preg_match('#^(projects/(.*?)/.*?):(.*?)\t([\.\d]+)\t(\d+)\t(\d+)\t(\d+)\t(\d+)#', $row, $r)) {
        continue; 
    }
    
    //                 $this->log->log("$analyzer_class\t".($end - $begin)."\t$count\t$processed\t$queries\t$rawQueries");
    // 1 : error log (+ project name)
    // 2 : project name
    // 3 : file name
    // 4 : duration
    // 5 : found issues
    // 6 : processed prospects
    // 7 : distinct queries count
    // 8 : total queries
    $final[] = $r[2];
    $final[] = $r[4];
    $final[] = $r[5];
    $final[] = $r[6];
    
    $sqliteFilename = str_replace('log/analyze.analyze.log', 'datastore.sqlite', $r[1]);
    if (!file_exists($sqliteFilename)) {
        print "No $sqliteFilename\n";
        continue; 
    }
    
    $sqlite = new \Sqlite3($sqliteFilename);

    $res = $sqlite->query('SELECT * FROM hash WHERE key = "tokens";');
    $sqlRow = $res->fetchArray();
    
    $final[] = $sqlRow['value'];
    $finals[] = $final;
}

$fp = fopen('timing2.csv', 'w+');
if ($fp === false) {
    foreach($finals as $final) {
        fputcsv($fp, $final);
    }
    fclose($fp);
}

?>