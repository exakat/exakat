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

print "Compare 2 projects's speed of analysing, based on logs\n";
$project1 = $argv[1];
$project2 = $argv[2];

if (empty($project1) || empty($project2) || ($project2 === $project1)) {
    die("Usage : php scripts/slow.php <project1> <project2>\n");
}

if (!file_exists('projects/'.$project1)) {
    print "project 1 $project1 doesn't exists\n";
    die("Usage : php scripts/slow.php <project1> <project2>\n");
}

if (!file_exists('projects/'.$project2)) {
    print "project 2 $project2 doesn't exists\n";
    die("Usage : php scripts/slow.php <project1> <project2>\n");
}

if (!file_exists('projects/'.$project1.'/log/analyze.analyze.log')) {
    print "project 1 $project1 has no logs. Run exakat first\n";
    die("Usage : php scripts/slow.php <project1> <project2>\n");
}

if (!file_exists('projects/'.$project2.'/log/analyze.analyze.log')) {
    print "project 2 $project2 has no logs. Run exakat first\n";
    die("Usage : php scripts/slow.php <project1> <project2>\n");
}

print "Comparing $project1 with $project2\n";

$log = file('projects/'.$project1.'/log/analyze.analyze.log');

$timing = array();
foreach($log as $line) {
    if (preg_match("#^(\S+/\S+)\t([0-9\.]+)\t#is", $line, $r)) {
        $timing[$r[1]] = $r[2];
    }
}

$log = file('projects/'.$project2.'/log/analyze.analyze.log');

$reference = array();
foreach($log as $line) {
    if (preg_match("#^(\S+/\S+)\t([0-9\.]+)\t#is", $line, $r)) {
        $reference[$r[1]] = $r[2];
    }
}

$ratio = array();
foreach($reference as $id => $t) {
    if (!isset($timing[$id])) { continue; }
    $ratio[$id] = $timing[$id] / $t;
}

asort($ratio);
print_r($ratio);

?>