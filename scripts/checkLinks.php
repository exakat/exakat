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


$project = 'ask';

$files = glob('projects/'.$project.'/report/ajax/*.html');

$totalLink = 0;
$totalFile = 0;
$totalMiss = 0;

foreach($files as $file) {
    ++$totalFile;
    $html = file_get_contents($file);
    
    preg_match_all('/href="\#?(.*?)"/is', $html, $r);
    
    $links = array_filter($r[1], function ($x) {
        if (empty($x)) { return false; }
        
        if (substr($x, 0, 4) == 'http') { return false; }
        if (substr($x, 0, 6) == 'mailto') { return false; }
        
        return true;
    });
    
    $leftLinks = array_filter($links, function ($x) use ($project) {
        return !file_exists('projects/'.$project.'/report/'.$x);
    });
    
    $totalLink += count($links) - count($leftLinks);
    $totalMiss += count($leftLinks);
    if (count($leftLinks) != 0) {
        print count($leftLinks) . ' are missing in '.$file."\n";
        print_r($leftLinks);
    }
}

print "Total files  : $totalFile\n";
print "Total links  : $totalLink\n";
print "Total missed : $totalMiss\n";

?>