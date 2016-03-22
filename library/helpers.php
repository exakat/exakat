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


function display($text) {
    $config = \Config::factory();
    
    if ($config->verbose) {
        echo trim($text), "\n";
    }
}

function display_r($object) {
    static $config;
    
    if ($config === null) {
        $config = \Config::factory();
    }
    
    if ($config->verbose) {
        print_r( $object );
    }
}

function rmdirRecursive($dir) { 
    if (!file_exists($dir)) { 
        // Do nothing
        return 0;
    }

    // Remove symlink, but not their content
    if (is_link($dir)) {
        unlink($dir);
        return 0;
    }

    if (empty($dir)) { 
        return 0;
    }
    
    $total = 0;
    $files = array_diff(scandir($dir), array('.','..')); 
    
    foreach ($files as $file) { 
        if (is_dir("$dir/$file")) {
            $total += rmdirRecursive("$dir/$file");
        } else {
            unlink("$dir/$file"); 
            ++$total;
        }
    } 

    rmdir($dir);
    ++$total;

    return $total; 
  } 

function copyDir($src, $dst) { 
    if (!file_exists($src)) { 
        return 0;
    }
    $dir = opendir($src); 
    if (!$dir) { return true; }
    
    $total = 0;
    mkdir($dst, 0755);
    while(false !==  $file = readdir($dir) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                $total += copyDir($src . '/' . $file,$dst . '/' . $file); 
            } else { 
                copy($src . '/' . $file, $dst . '/' . $file); 
                ++$total;
            } 
        } 
    } 
    
    closedir($dir); 

    return $total;
} 

?>