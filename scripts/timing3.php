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


$rows = glob('projects/*');

$finals = [['project', 'Duree', 'Tokens', 'LoC', 'Neo4jSize']];
foreach($rows as $row) {
    $final = [basename($row)];
    
    if (!is_dir($row)) { continue; }
    
    if (!file_exists($row.'/log/project.timing.csv')) {
        print "$row has no log/project.timing.csv\n";
        continue;
    }
    $csv = file_get_contents($row.'/log/project.timing.csv');
    if (!preg_match('/Final\t([\d\.]+)\t([\d\.]+)/is' , $csv, $r)) {
        print "$row has no Final in log/project.timing.csv\n";
        continue;
    }
    $final[] = $r[2];
        
    $sqliteFilename = $row.'/datastore.sqlite';
    if (!file_exists($sqliteFilename)) {
        print "No $sqliteFilename\n";
        continue; 
    }
    
    $sqlite = new \Sqlite3($sqliteFilename);

    $res = $sqlite->query('SELECT * FROM hash WHERE key = "tokens";');
    $sqlRow = $res->fetchArray();
    $final[] = $sqlRow['value'];

    $res = $sqlite->query('SELECT * FROM hash WHERE key = "loc";');
    $sqlRow = $res->fetchArray();
    $final[] = $sqlRow['value'];

    $res = $sqlite->query('SELECT * FROM hash WHERE key = "neo4jSize";');
    $sqlRow = $res->fetchArray();
    if (is_array($sqlRow)) {
        preg_match('/\d+[KMG]/', $sqlRow['value'], $size);
        $size = $size[0];
        $size = str_replace('K', '000', $size);
        $size = str_replace('M', '000000', $size);
        $size = str_replace('G', '000000000', $size);
        $final[] = $size;
    } else {
        $final[] = '';
    }
    
    $finals[] = $final;
}

$fp = fopen('timing3.csv', 'w+');
foreach($finals as $final) {
    fputcsv($fp, $final);
}
fclose($fp);

?>