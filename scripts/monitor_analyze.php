<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


$project = $argv[1];

$done = file('log/analyze.log');
array_shift($done);
array_shift($done);
array_shift($done);
array_shift($done);

foreach($done as $i => $v) {
    list($a, $t,) = explode("\t", trim($v));
    $done[$i] = $a;
    $times[] = $t;
}

sort($times);
$eta = $times[floor(count($times) / 2)];

$config = parse_ini_file('projects/'.$project.'/config.ini');

//print_r($config['analyzer']);

$diff = array_diff($config['analyzer'], $done);

print_r($diff);
print count($diff) .' / '. count($config['analyzer'])." left (".number_format( count($diff) / count($config['analyzer']) * 100, 2)." %)\n";
$ceta = ($eta * count($diff));
if ($ceta < 60) {
    $peta = $ceta." s";
} elseif ($ceta < 3600) {
    $peta = floor($ceta / 60)." m ".floor($ceta - floor($ceta / 60) * 60)." s";
} else {
    $peta = $ceta." s";
}
print "ETA : ". $peta." ( ".date( 'r', time() + $ceta).") \n";

?>