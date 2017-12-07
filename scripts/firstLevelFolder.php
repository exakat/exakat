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


print "list all first-level files in projects for stats\n";
$res = shell_exec('find projects/*/code -mindepth 1 -maxdepth 1 -type f -ls');

$files = explode("\n", $res);
$files = array_map('basename', $files);
$counts = array_count_values($files);

foreach($counts as $k => $v) {
    if ($v < 20) {
        unset($counts[$k]);
    }
}
asort($counts);
print_r($counts);


print "list all first-level folders in projects for stats\n";
$res = shell_exec('find projects/[a-d]* -depth 2 -type d -path "*/code/*"');
print_r($res);

$stats = array();
$dirs = explode("\n", $res);
foreach($dirs as $dir) {
    $dir = basename($dir);
    $stats[] = $dir;
}

$stats = array_count_values($stats);
asort($stats);
print_r($stats);
?>